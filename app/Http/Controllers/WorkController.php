<?php

namespace App\Http\Controllers;

use App\Enums\CommonStatusEnum;
use App\Models\Project;
use App\Models\Service;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class WorkController extends Controller
{
    private const PER_PAGE = 9;

    public function index(Request $request)
    {
        // Only services that have at least one live project become filter tabs.
        $services = Service::active()
            ->ordered()
            ->whereHas('projects', fn (Builder $q) => $q->active())
            ->withCount(['projects' => fn (Builder $q) => $q->active()])
            ->get();

        $activeService = $services->firstWhere('slug', $request->query('service'));

        $projects = Project::active()
            ->with('service')
            ->when($activeService, fn (Builder $q) => $q->where('service_id', $activeService->id))
            ->ordered()
            ->paginate(self::PER_PAGE)
            ->withQueryString();

        // A page past the end is not an empty listing: return a real 404 (no "soft 404" for search engines).
        abort_if($projects->currentPage() > max($projects->lastPage(), 1), 404);

        return view('website.work.index', [
            'projects' => $projects,
            'services' => $services,
            'activeService' => $activeService,
            'totalProjects' => $activeService ? Project::active()->count() : $projects->total(),
        ]);
    }

    public function show(string $slug)
    {
        $project = Project::active()
            ->with(['service', 'seo'])
            ->where('slug', $slug)
            ->firstOrFail();

        // Same-service projects first, then the most recent others.
        $more = Project::active()
            ->with('service')
            ->whereKeyNot($project->id)
            ->when($project->service_id, fn (Builder $q) => $q->orderByRaw('service_id = ? DESC', [$project->service_id]))
            ->ordered()
            ->take(3)
            ->get();

        // Only link the project to its service while that service is published.
        $service = $project->service?->status === CommonStatusEnum::ACTIVE ? $project->service : null;

        return view('website.work.show', compact('project', 'more', 'service'));
    }
}
