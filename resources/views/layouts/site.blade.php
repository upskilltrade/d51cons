{{--
    Structural public layout. The frontend pass replaces markup and styling;
    keep the SEO head (title, description, canonical, hreflang) and the data contracts.
--}}
@php
    $locale = app()->getLocale();
    $settings = \App\Models\Setting::allValues();
    $title = trim(($title ?? '') . ' | ' . (\App\Models\Setting::localized('seo_title') ?: config('app.name')), ' |');
    $description = $description ?? \App\Models\Setting::localized('seo_description');
    $route = request()->route();
    $alternates = collect(config('d51.locales'))->mapWithKeys(fn ($l) => [
        $l => $route?->getName() ? route($route->getName(), [...$route->parameters(), 'locale' => $l]) : url($l),
    ]);
@endphp
<!DOCTYPE html>
<html lang="{{ $locale }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title }}</title>
    @if ($description)
        <meta name="description" content="{{ $description }}">
    @endif
    <link rel="canonical" href="{{ $alternates[$locale] }}">
    @foreach ($alternates as $l => $href)
        <link rel="alternate" hreflang="{{ $l }}" href="{{ $href }}">
    @endforeach
    <link rel="alternate" hreflang="x-default" href="{{ $alternates[config('d51.default_locale')] }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <header>
        <a href="{{ route('home') }}">D51 Construction</a>
        <nav aria-label="Main">
            <a href="{{ route('services.index') }}">Services</a>
            <a href="{{ route('projects.index') }}">Projects</a>
            <a href="{{ route('about') }}">About</a>
            <a href="{{ route('contact') }}">Contact</a>
        </nav>
        <nav aria-label="Language">
            @foreach ($alternates as $l => $href)
                <a href="{{ $href }}" hreflang="{{ $l }}" @if ($l === $locale) aria-current="true" @endif>{{ strtoupper($l) }}</a>
            @endforeach
        </nav>
    </header>

    <main>
        @yield('content')
    </main>

    <footer>
        {{-- Only channels with a real value are rendered. --}}
        @foreach (['phone' => 'tel:', 'email' => 'mailto:'] as $key => $scheme)
            @if ($settings[$key])
                <a href="{{ $scheme . $settings[$key] }}">{{ $settings[$key] }}</a>
            @endif
        @endforeach
        @foreach (['line_url', 'whatsapp_url', 'facebook_url', 'instagram_url'] as $key)
            @if ($settings[$key])
                <a href="{{ $settings[$key] }}" rel="noopener">{{ str($key)->before('_url')->headline() }}</a>
            @endif
        @endforeach
        <a href="{{ route('privacy') }}">Privacy</a>
    </footer>
</body>
</html>
