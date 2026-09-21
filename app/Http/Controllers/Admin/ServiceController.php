<?php

namespace App\Http\Controllers\Admin;

use App\Enums\CommonStatusEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ServiceStoreRequest;
use App\Http\Requests\Admin\ServiceUpdateRequest;
use App\Models\Service;
use App\Services\ImageProcessor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class ServiceController extends Controller
{
    public function index(Request $request)
    {
        Gate::authorize('admin.services.view');

        $query = Service::with('user');

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%'.$request->search.'%')
                    ->orWhere('excerpt', 'like', '%'.$request->search.'%');
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $services = $query->ordered()
            ->paginate(20)
            ->withQueryString();

        return view('admin.services.index', compact('services'));
    }

    public function create()
    {
        Gate::authorize('admin.services.create');

        return view('admin.services.create');
    }

    public function store(ServiceStoreRequest $request)
    {
        Gate::authorize('admin.services.create');

        $data = $request->validated();

        $data['user_id'] = Auth::id();
        $data['sort_order'] ??= (int) Service::max('sort_order') + 1;

        if ($request->hasFile('featured_image')) {
            $data['featured_image'] = app(ImageProcessor::class)->store($request->file('featured_image'), 'service', $request->input('featured_image_crop'));
        }

        unset($data['featured_image_crop']);

        $service = Service::create($data);

        if (Gate::allows('admin.services.edit')) {
            return to_route('admin.services.edit', $service)->with('success', 'Service Created');
        }

        return to_route('admin.services.index')->with('success', 'Service Created');
    }

    public function edit(Service $service)
    {
        Gate::authorize('admin.services.edit');

        $service->load('seo');

        return view('admin.services.edit', compact('service'));
    }

    public function update(ServiceUpdateRequest $request, Service $service)
    {
        Gate::authorize('admin.services.edit');

        $data = $request->validated();
        $data['sort_order'] ??= $service->sort_order;

        if ($request->hasFile('featured_image')) {
            $data['featured_image'] = app(ImageProcessor::class)->store($request->file('featured_image'), 'service', $request->input('featured_image_crop'), $service->featured_image);
        } elseif ($request->boolean('remove_image')) {
            if ($service->featured_image) {
                app(ImageProcessor::class)->delete($service->featured_image);
            }
            $data['featured_image'] = null;
        }

        unset($data['remove_image'], $data['featured_image_crop']);

        $service->update($data);

        return back()->with('success', 'Service Updated');
    }

    public function destroy(Service $service)
    {
        Gate::authorize('admin.services.delete');

        $service->delete();

        return to_route('admin.services.index')->with('success', 'Service deleted');
    }

    public function toggleStatus(Service $service)
    {
        Gate::authorize('admin.services.toogle-status');

        $service->status = $service->status === CommonStatusEnum::ACTIVE ? CommonStatusEnum::INACTIVE : CommonStatusEnum::ACTIVE;
        $service->save();

        return response()->json([
            'status' => $service->status,
            'message' => 'Status updated successfully',
        ]);
    }
}
