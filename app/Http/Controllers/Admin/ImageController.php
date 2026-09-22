<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class ImageController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        // Only people who can write rich-text content may upload images for the editor.
        abort_unless(Gate::any([
            'admin.blogs.create', 'admin.blogs.edit',
            'admin.pages.create', 'admin.pages.edit',
            'admin.services.create', 'admin.services.edit',
            'admin.projects.create', 'admin.projects.edit',
        ]), 403);

        $request->validate([
            'file' => ['required', 'image', 'max:2048', 'mimes:jpg,jpeg,png,webp', 'dimensions:max_width=8000,max_height=8000'],
        ]);

        $path = $request->file('file')->store(sprintf('uploads/%s/%s/miscellaneous', now()->year, now()->month), 'public');

        return response()->json(['location' => asset('storage/'.$path)]);
    }
}
