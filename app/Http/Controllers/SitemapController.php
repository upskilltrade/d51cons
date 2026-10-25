<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Service;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function __invoke(): Response
    {
        $urls = [];

        foreach (config('d51.locales') as $locale) {
            foreach (['home', 'services.index', 'projects.index', 'about', 'contact', 'privacy'] as $name) {
                $urls[] = ['loc' => route($name, ['locale' => $locale]), 'lastmod' => null];
            }

            foreach (Service::query()->published()->ordered()->get() as $service) {
                $urls[] = ['loc' => route('services.show', ['locale' => $locale, 'service' => $service]), 'lastmod' => $service->updated_at];
            }

            foreach (Project::query()->published()->ordered()->get() as $project) {
                $urls[] = ['loc' => route('projects.show', ['locale' => $locale, 'project' => $project]), 'lastmod' => $project->updated_at];
            }
        }

        return response()
            ->view('sitemap', ['urls' => $urls])
            ->header('Content-Type', 'application/xml');
    }
}
