<?php

namespace App\Http\Controllers\Admin;

use App\Enums\ProjectStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ProjectRequest;
use App\Models\Project;
use App\Models\Service;
use App\Support\Media;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ProjectController extends Controller
{
    public function index(): View
    {
        return view('admin.projects.index', [
            'projects' => Project::query()->with('service')->ordered()->paginate(30),
        ]);
    }

    public function create(): View
    {
        return $this->form(new Project(['status' => ProjectStatus::Planning, 'is_concept' => false]));
    }

    public function store(ProjectRequest $request): RedirectResponse
    {
        $project = new Project;
        $this->fill($project, $request);

        return redirect()->route('admin.projects.edit', $project)->with('status', __('admin.saved'));
    }

    public function edit(Project $project): View
    {
        return $this->form($project->load('images'));
    }

    public function update(ProjectRequest $request, Project $project): RedirectResponse
    {
        $this->fill($project, $request);

        return redirect()->route('admin.projects.edit', $project)->with('status', __('admin.saved'));
    }

    public function destroy(Project $project): RedirectResponse
    {
        Media::delete($project->cover_path);
        $project->images->each(fn ($image) => Media::delete($image->path));
        $project->delete();

        return redirect()->route('admin.projects.index')->with('status', __('admin.deleted'));
    }

    private function form(Project $project): View
    {
        return view('admin.projects.form', [
            'project' => $project,
            'services' => Service::query()->ordered()->get(),
            'statuses' => ProjectStatus::cases(),
        ]);
    }

    private function fill(Project $project, ProjectRequest $request): void
    {
        $project->fill($request->safe()->except('cover'));
        $project->sort_order ??= 0;

        if ($request->hasFile('cover')) {
            Media::delete($project->cover_path);
            $project->cover_path = Media::store($request->file('cover'), 'projects');
        }

        $project->save();
    }
}
