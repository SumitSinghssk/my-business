<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use App\Services\ImageProcessor;
use App\Support\ImagePreset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    public function edit(Request $request)
    {
        Gate::authorize('profile.view');

        return view('admin.auth.profile', [
            'user' => $request->user(),
        ]);
    }

    public function update(Request $request)
    {
        Gate::authorize('profile.update');

        $user = $request->user();

        $avatarPreset = ImagePreset::get('avatar');

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'bio' => 'nullable|string',
            ...$avatarPreset->rules('avatar'),
            'social_links' => 'nullable|array',
            'social_links.*' => 'nullable|url',
            'remove_avatar' => 'nullable|boolean',
        ], $avatarPreset->messages('avatar'));

        unset($validated['avatar_crop']);

        if ($request->remove_avatar) {
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }

            $validated['avatar'] = null;
        }

        if ($request->hasFile('avatar')) {
            // The old file is only removed once the new one has been saved.
            $validated['avatar'] = app(ImageProcessor::class)->store($request->file('avatar'), 'avatar', $request->input('avatar_crop'), $user->avatar);
        }

        $user->update($validated);

        return back()->with('status', 'profile-updated');
    }

    public function updatePassword(Request $request)
    {
        Gate::authorize('profile.update-password');

        $request->validateWithBag('updatePassword', [
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $request->user()->update([
            'password' => Hash::make($request->password),
        ]);

        return back()->with('status', 'password-updated');
    }
}
