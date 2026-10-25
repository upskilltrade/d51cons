<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Service;
use Illuminate\View\View;

class PageController extends Controller
{
    public function home(): View
    {
        return view('pages.home', [
            'services' => Service::query()->published()->ordered()->limit(4)->get(),
            'projects' => Project::query()->published()->actual()->ordered()->limit(3)->get(),
            'concepts' => Project::query()->published()->concept()->ordered()->limit(3)->get(),
        ]);
    }

    public function about(): View
    {
        return view('pages.about');
    }

    public function privacy(): View
    {
        return view('pages.privacy');
    }
}
