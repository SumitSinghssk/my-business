<?php

namespace App\Http\Controllers\Admin\Setting;

use App\Support\Robots;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class RobotsController
{
    public function __invoke(Request $request)
    {
        Gate::authorize('admin.settings.robots.update');

        $request->validate([
            'content' => 'nullable|string|max:65535',
        ]);

        try {
            $written = Robots::save($request->content ?? '');
        } catch (\Throwable $e) {
            $written = false;
        }

        if ($written === false) {
            return back()->with('error', 'Failed to update robots.txt. Please check that the public directory is writable.');
        }

        return back()->with('success', 'Robots.txt updated successfully');
    }
}
