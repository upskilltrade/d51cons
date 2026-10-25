<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ServiceRequest;
use App\Models\Service;
use App\Support\Media;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ServiceController extends Controller
{
    public function index(): View
    {
        return view('admin.services.index', [
            'services' => Service::query()->ordered()->get(),
        ]);
    }

    public function create(): View
    {
        return view('admin.services.form', ['service' => new Service(['is_published' => false])]);
    }

    public function store(ServiceRequest $request): RedirectResponse
    {
        $service = new Service;
        $this->fill($service, $request);

        return redirect()->route('admin.services.edit', $service)->with('status', __('admin.saved'));
    }

    public function edit(Service $service): View
    {
        return view('admin.services.form', ['service' => $service]);
    }

    public function update(ServiceRequest $request, Service $service): RedirectResponse
    {
        $this->fill($service, $request);

        return redirect()->route('admin.services.edit', $service)->with('status', __('admin.saved'));
    }

    public function destroy(Service $service): RedirectResponse
    {
        Media::delete($service->image_path);
        $service->delete();

        return redirect()->route('admin.services.index')->with('status', __('admin.deleted'));
    }

    private function fill(Service $service, ServiceRequest $request): void
    {
        $service->fill($request->safe()->except(['image', 'remove_image']));
        $service->sort_order ??= 0;

        if ($request->hasFile('image') || $request->boolean('remove_image')) {
            Media::delete($service->image_path);
            $service->image_path = $request->hasFile('image')
                ? Media::store($request->file('image'), 'services')
                : null;
        }

        $service->save();
    }
}
