<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ProjectImage;
use App\Support\Media;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ProjectImageController extends Controller
{
    public function store(Request $request, Project $project): RedirectResponse
    {
        $request->validate([
            'images' => ['required', 'array', 'max:20'],
            'images.*' => Media::rules(),
        ]);

        $next = (int) $project->images()->max('sort_order');

        foreach ($request->file('images') as $file) {
            $project->images()->create([
                'path' => Media::store($file, 'projects/'.$project->id),
                'sort_order' => ++$next,
            ]);
        }

        return back()->with('status', __('admin.saved'));
    }

    public function update(Request $request, Project $project, ProjectImage $image): RedirectResponse
    {
        $image->update($request->validate([
            'alt_th' => ['nullable', 'string', 'max:190'],
            'alt_en' => ['nullable', 'string', 'max:190'],
            'caption_th' => ['nullable', 'string', 'max:190'],
            'caption_en' => ['nullable', 'string', 'max:190'],
            'sort_order' => ['required', 'integer', 'min:0', 'max:9999'],
        ]));

        return back()->with('status', __('admin.saved'));
    }

    public function destroy(Project $project, ProjectImage $image): RedirectResponse
    {
        Media::delete($image->path);
        $image->delete();

        return back()->with('status', __('admin.deleted'));
    }
}
