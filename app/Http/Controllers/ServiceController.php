<?php

namespace App\Http\Controllers;

use App\Models\Service;

class ServiceController extends Controller
{
    public function index()
    {
        return view('website.services.index', [
            'services' => Service::active()->ordered()->get(),
        ]);
    }

    public function show(string $slug)
    {
        $service = Service::active()
            ->with('seo')
            ->where('slug', $slug)
            ->firstOrFail();

        $others = Service::active()
            ->ordered()
            ->whereKeyNot($service->id)
            ->get();

        return view('website.services.show', [
            'service' => $service,
            'others' => $others,
            'projects' => $service->projects()->active()->ordered()->take(3)->get(),
        ]);
    }
}
