<?php

namespace App\Http\Controllers\Admin;

use App\Enums\CommonStatusEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UserStoreRequest;
use App\Http\Requests\Admin\UserUpdateRequest;
use App\Models\User;
use App\Services\ImageProcessor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    /**
     * Roles that only a super admin may assign or revoke.
     */
    private const PROTECTED_ROLES = ['super admin', 'super-admin'];

    public function index(Request $request)
    {
        Gate::authorize('admin.users.view');

        $query = User::with('roles');

        // Filter by Search (Name or Email)
        if ($request->filled('search')) {
            $term = '%'.addcslashes($request->search, '%_\\').'%';
            $query->where(function ($q) use ($term) {
                $q->where('name', 'like', $term)
                    ->orWhere('email', 'like', $term);
            });
        }

        // Filter by Status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by Role (Spatie HasRoles trait scope)
        if ($request->filled('role') && is_string($request->role)) {
            // whereHas instead of the role() scope, which throws on unknown role names.
            $query->whereHas('roles', fn ($q) => $q->where('name', $request->role));
        }

        $users = $query->latest()
            ->paginate(20)
            ->withQueryString();

        // Get all roles for the filter dropdown
        $roles = Role::all();

        return view('admin.users.index', compact('users', 'roles'));
    }

    public function create()
    {
        Gate::authorize('admin.users.create');

        $roles = Role::with('permissions')->orderBy('name')->get();
        $permissions = Permission::orderBy('name')->get();

        $groupedPermissions = $permissions->groupBy(function ($permission) {
            $parts = explode('.', $permission->name);
            if ($parts[0] === 'admin' && isset($parts[1])) {
                return ucfirst(str_replace('-', ' ', $parts[1]));
            }

            return ucfirst(str_replace('-', ' ', $parts[0]));
        })->sortKeys();

        $rolesWithPermissions = $roles->map(fn ($r) => [
            'name' => $r->name,
            'permissions' => $r->permissions->pluck('name')->values()->toArray(),
            'count' => $r->permissions->count(),
        ])->values()->toArray();

        return view('admin.users.create', compact(
            'roles', 'groupedPermissions', 'rolesWithPermissions'
        ));
    }

    public function store(UserStoreRequest $request)
    {
        Gate::authorize('admin.users.create');

        $avatarPath = null;
        if ($request->hasFile('avatar')) {
            $avatarPath = app(ImageProcessor::class)->store($request->file('avatar'), 'avatar', $request->input('avatar_crop'));
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password,
            'status' => $request->status,
            'bio' => $request->bio,
            'avatar' => $avatarPath,
        ]);

        $user->syncRoles($this->resolveRoles((array) $request->roles));
        $user->syncPermissions($this->resolvePermissions((array) $request->permissions));

        if (Gate::allows('admin.users.edit')) {
            return to_route('admin.users.edit', $user)->with('success', 'User Created');
        }

        return to_route('admin.users.index')->with('success', 'User Created');
    }

    public function edit(User $user)
    {
        Gate::authorize('admin.users.edit');
        $this->ensureCanManage($user);

        $roles = Role::with('permissions')->orderBy('name')->get();
        $permissions = Permission::orderBy('name')->get();

        $groupedPermissions = $permissions->groupBy(function ($permission) {
            $parts = explode('.', $permission->name);
            if ($parts[0] === 'admin' && isset($parts[1])) {
                return ucfirst(str_replace('-', ' ', $parts[1]));
            }

            return ucfirst(str_replace('-', ' ', $parts[0]));
        })->sortKeys();

        $rolesWithPermissions = $roles->map(fn ($r) => [
            'name' => $r->name,
            'permissions' => $r->permissions->pluck('name')->values()->toArray(),
            'count' => $r->permissions->count(),
        ])->values()->toArray();

        $userRoles = $user->roles->pluck('name')->values()->toArray();
        $userPermissions = $user->getDirectPermissions()->pluck('name')->values()->toArray();

        return view('admin.users.edit', compact(
            'user', 'roles', 'groupedPermissions',
            'rolesWithPermissions', 'userRoles', 'userPermissions'
        ));
    }

    public function update(UserUpdateRequest $request, User $user)
    {
        Gate::authorize('admin.users.edit');
        $this->ensureCanManage($user);

        // Only a super admin may change their own status, roles or permissions.
        $editingSelf = $user->is(auth()->user()) && ! $this->actorIsSuperAdmin();

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'status' => $editingSelf ? $user->status : $request->status,
            'bio' => $request->bio,
        ];

        if ($request->filled('password')) {
            $data['password'] = $request->password;
        }

        if ($request->hasFile('avatar')) {
            // The old file is only removed once the new one has been saved.
            $data['avatar'] = app(ImageProcessor::class)->store($request->file('avatar'), 'avatar', $request->input('avatar_crop'), $user->avatar);
        } elseif ($request->boolean('remove_avatar')) {
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }
            $data['avatar'] = null;
        }

        $user->update($data);

        if ($request->filled('password')) {
            // Remember-me cookies stop working; open sessions end through the auth.session middleware.
            $user->forceFill(['remember_token' => Str::random(60)])->save();
        }

        if (! $editingSelf) {
            $user->syncRoles($this->resolveRoles((array) ($request->roles ?? []), $user));
            $user->syncPermissions($this->resolvePermissions((array) ($request->permissions ?? []), $user));
        }

        return back()->with('success', "User \"{$user->name}\" updated successfully.");
    }

    public function destroy(User $user)
    {
        Gate::authorize('admin.users.delete');

        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot delete your own account.');
        }

        $this->ensureCanManage($user);

        if ($user->avatar) {
            Storage::disk('public')->delete($user->avatar);
        }

        $user->delete();

        return to_route('admin.users.index')->with('success', "User \"{$user->name}\" deleted successfully.");
    }

    public function toggleStatus(User $user)
    {
        Gate::authorize('admin.users.toogle-status');

        if ($user->id === auth()->id()) {
            return response()->json([
                'message' => 'You cannot change your own account status.',
            ], 422);
        }

        if (! $this->canManage($user)) {
            return response()->json([
                'message' => 'You are not allowed to change this account\'s status.',
            ], 403);
        }

        $user->status = $user->status === CommonStatusEnum::ACTIVE ? CommonStatusEnum::INACTIVE : CommonStatusEnum::ACTIVE;
        $user->save();

        return response()->json([
            'status' => $user->status,
            'message' => 'Status updated successfully',
        ]);
    }

    /**
     * Restrict which roles the current actor is allowed to assign.
     *
     * A non super admin can never grant a protected role, and (when editing an
     * existing user) can never strip a protected role the target already holds.
     */
    private function resolveRoles(array $requested, ?User $target = null): array
    {
        if ($this->actorIsSuperAdmin()) {
            return $requested;
        }

        // A non super admin may only hand out roles whose permissions they already hold,
        // otherwise assigning a role would grant more access than the actor has.
        $allowed = auth()->user()->getAllPermissions()->pluck('name');
        $assignable = Role::with('permissions')->whereNotIn('name', self::PROTECTED_ROLES)->get()
            ->filter(fn (Role $role) => $role->permissions->pluck('name')->diff($allowed)->isEmpty())
            ->pluck('name');

        $requested = array_values(array_intersect($requested, $assignable->all()));

        if ($target) {
            // Keep roles the target already has that the actor is not allowed to manage.
            $kept = $target->roles->pluck('name')->diff($assignable)->all();
            $requested = array_values(array_unique(array_merge($requested, $kept)));
        }

        return $requested;
    }

    /**
     * Restrict which direct permissions the current actor is allowed to assign.
     *
     * A non super admin can only grant permissions they themselves hold, and
     * cannot strip permissions a target already holds beyond the actor's own set.
     */
    private function resolvePermissions(array $requested, ?User $target = null): array
    {
        $actor = auth()->user();

        if ($this->actorIsSuperAdmin()) {
            return $requested;
        }

        $allowed = $actor->getAllPermissions()->pluck('name')->all();
        $requested = array_values(array_intersect($requested, $allowed));

        if ($target) {
            $existingBeyondActor = $target->getDirectPermissions()->pluck('name')
                ->diff($allowed)->all();
            $requested = array_values(array_unique(array_merge($requested, $existingBeyondActor)));
        }

        return $requested;
    }

    private function actorIsSuperAdmin(): bool
    {
        return auth()->user()->hasRole(self::PROTECTED_ROLES);
    }

    /**
     * Only a super admin may manage a super admin account, and nobody may manage an account
     * holding permissions they lack themselves (resetting its password would hand them that access).
     */
    private function canManage(User $user): bool
    {
        if ($this->actorIsSuperAdmin()) {
            return true;
        }

        if ($user->hasRole(self::PROTECTED_ROLES)) {
            return false;
        }

        return $user->getAllPermissions()->pluck('name')
            ->diff(auth()->user()->getAllPermissions()->pluck('name'))
            ->isEmpty();
    }

    private function ensureCanManage(User $user): void
    {
        abort_unless($this->canManage($user), 403, 'You are not allowed to manage this account.');
    }
}
