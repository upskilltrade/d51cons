<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SettingController extends Controller
{
    private const URL_KEYS = ['line_url', 'whatsapp_url', 'facebook_url', 'instagram_url', 'map_url'];

    public function edit(): View
    {
        return view('admin.settings.edit', ['settings' => Setting::allValues()]);
    }

    public function update(Request $request): RedirectResponse
    {
        $rules = collect(config('d51.settings'))->mapWithKeys(fn (string $key) => [$key => match (true) {
            in_array($key, self::URL_KEYS, true) => ['nullable', 'url:https', 'max:500'],
            $key === 'email' => ['nullable', 'email:rfc', 'max:190'],
            $key === 'phone' => ['nullable', 'string', 'max:40', 'regex:/^[0-9+()\-\s]+$/'],
            str_starts_with($key, 'about_') => ['nullable', 'string', 'max:10000'],
            default => ['nullable', 'string', 'max:1000'],
        }])->all();

        Setting::saveMany($request->validate($rules));

        return back()->with('status', __('admin.saved'));
    }
}
