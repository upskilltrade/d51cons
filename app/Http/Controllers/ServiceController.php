<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\View\View;

class ServiceController extends Controller
{
    public function index(): View
    {
        return view('services.index', [
            'services' => Service::query()->published()->ordered()->get(),
        ]);
    }

    public function show(Service $service): View
    {
        abort_unless($service->is_published, 404);

        return view('services.show', [
            'service' => $service,
            'projects' => $service->projects()->published()->actual()->ordered()->limit(6)->get(),
        ]);
    }
}
