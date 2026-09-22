<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        Gate::authorize('admin.notifications.view');

        $notifications = Notification::visibleTo($request->user())->latest()
            ->take(10)
            ->get()
            ->map(function ($n) {
                return [
                    'id' => $n->id,
                    'title' => $n->title,
                    'type' => $n->type,
                    'message' => $n->message,
                    'url' => $n->url,
                    'seen' => ! is_null($n->seen_at),
                    'time' => $n->created_at->diffForHumans(),
                ];
            });

        return response()->json([
            'notifications' => $notifications,
            'unread_count' => Notification::visibleTo($request->user())->whereNull('seen_at')->count(),
        ]);
    }

    public function list(Request $request)
    {
        Gate::authorize('admin.notifications.view');

        $notifications = Notification::visibleTo($request->user())->orderByRaw('seen_at IS NULL DESC')->latest()->paginate(15);

        return view('admin.notifications.index', compact('notifications'));
    }

    public function view(Request $request, $id)
    {
        Gate::authorize('admin.notifications.view');

        $notification = Notification::visibleTo($request->user())->findOrFail($id);

        if (is_null($notification->seen_at)) {
            $notification->update([
                'seen_at' => now(),
            ]);
        }

        $target = $notification->url ?? route('admin.dashboard');

        // Only follow links into this site: a stored URL must never become an open redirect
        // (checking the parsed host alone misses tricks like "https:\\evil.com").
        $base = rtrim(url('/'), '/').'/';
        if (! str_starts_with($target.'/', $base) || str_contains($target, '\\')) {
            $target = route('admin.dashboard');
        }

        return redirect($target);
    }

    public function markAllRead(Request $request)
    {
        Gate::authorize('admin.notifications.mark-all-as-read');

        Notification::visibleTo($request->user())->whereNull('seen_at')->update(['seen_at' => now()]);

        return response()->json(['success' => true]);
    }

    public function markRead(Request $request, $id)
    {
        Gate::authorize('admin.notifications.view');

        $notification = Notification::visibleTo($request->user())->findOrFail($id);

        if (is_null($notification->seen_at)) {
            $notification->update(['seen_at' => now()]);
        }

        return response()->json(['success' => true]);
    }
}
