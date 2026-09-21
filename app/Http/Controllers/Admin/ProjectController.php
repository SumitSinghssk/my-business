<?php

namespace App\Http\Controllers\Admin;

use App\Enums\CommonStatusEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ProjectStoreRequest;
use App\Http\Requests\Admin\ProjectUpdateRequest;
use App\Models\Project;
use App\Models\Service;
use App\Services\ImageProcessor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class ProjectController extends Controller
{
    public function index(Request $request)
    {
        Gate::authorize('admin.projects.view');

        $query = Project::with('service');

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%'.$request->search.'%')
                    ->orWhere('client', 'like', '%'.$request->search.'%')
                    ->orWhere('industry', 'like', '%'.$request->search.'%');
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $projects = $query->ordered()
            ->paginate(20)
            ->withQueryString();

        return view('admin.projects.index', compact('projects'));
    }

    public function create()
    {
        Gate::authorize('admin.projects.create');

        return view('admin.projects.create', ['services' => $this->serviceOptions()]);
    }

    public function store(ProjectStoreRequest $request)
    {
        Gate::authorize('admin.projects.create');

        $data = $request->validated();

        $data['user_id'] = Auth::id();
        $data['sort_order'] ??= (int) Project::max('sort_order') + 1;

        if ($request->hasFile('featured_image')) {
            $data['featured_image'] = app(ImageProcessor::class)->store($request->file('featured_image'), 'project', $request->input('featured_image_crop'));
        }

        unset($data['featured_image_crop']);

        $project = Project::create($data);

        if (Gate::allows('admin.projects.edit')) {
            return to_route('admin.projects.edit', $project)->with('success', 'Project Created');
        }

        return to_route('admin.projects.index')->with('success', 'Project Created');
    }

    public function edit(Project $project)
    {
        Gate::authorize('admin.projects.edit');

        $project->load('seo');

        return view('admin.projects.edit', ['project' => $project, 'services' => $this->serviceOptions()]);
    }

    public function update(ProjectUpdateRequest $request, Project $project)
    {
        Gate::authorize('admin.projects.edit');

        $data = $request->validated();
        $data['sort_order'] ??= $project->sort_order;

        if ($request->hasFile('featured_image')) {
            $data['featured_image'] = app(ImageProcessor::class)->store($request->file('featured_image'), 'project', $request->input('featured_image_crop'), $project->featured_image);
        } elseif ($request->boolean('remove_image')) {
            if ($project->featured_image) {
                app(ImageProcessor::class)->delete($project->featured_image);
            }
            $data['featured_image'] = null;
        }

        unset($data['remove_image'], $data['featured_image_crop']);

        $project->update($data);

        return back()->with('success', 'Project Updated');
    }

    public function destroy(Project $project)
    {
        Gate::authorize('admin.projects.delete');

        $project->delete();

        return to_route('admin.projects.index')->with('success', 'Project deleted');
    }

    public function toggleStatus(Project $project)
    {
        Gate::authorize('admin.projects.toogle-status');

        $project->status = $project->status === CommonStatusEnum::ACTIVE ? CommonStatusEnum::INACTIVE : CommonStatusEnum::ACTIVE;
        $project->save();

        return response()->json([
            'status' => $project->status,
            'message' => 'Status updated successfully',
        ]);
    }

    /** @return array<int, string> service id => title */
    private function serviceOptions(): array
    {
        return Service::ordered()->pluck('title', 'id')->all();
    }
}
