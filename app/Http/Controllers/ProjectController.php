<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProjectController extends Controller
{
    public function index(Request $request): View
    {
        // Only service types that have real projects to show become filters.
        $categories = Service::query()->published()
            ->whereHas('projects', fn ($query) => $query->published()->actual())
            ->ordered()->get();
        $category = $categories->firstWhere('slug', $request->query('category'));

        $projects = Project::query()
            ->published()
            ->actual()
            ->when($category, fn ($query) => $query->whereBelongsTo($category))
            ->with('service')
            ->ordered()
            ->paginate(12)
            ->withQueryString();

        return view('projects.index', [
            'projects' => $projects,
            'categories' => $categories,
            'category' => $category,
            // Shown in a separate, clearly labeled "Design inspiration" section.
            'concepts' => Project::query()->published()->concept()->ordered()->get(),
        ]);
    }

    public function show(Project $project): View
    {
        abort_unless($project->isPublished(), 404);

        $project->load(['service', 'images']);

        return view('projects.show', ['project' => $project]);
    }
}
