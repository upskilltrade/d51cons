@php
    $locale = app()->getLocale();
    $settings = \App\Models\Setting::allValues();
    $siteName = \App\Models\Setting::localized('seo_title') ?: __('site.seo.title');
    $title = trim(($title ?? '') . ' | ' . $siteName, ' |');
    $description = ($description ?? null) ?: (\App\Models\Setting::localized('seo_description') ?: __('site.seo.description'));
    $route = request()->route();
    $routeName = $route?->getName();
    $alternates = collect(config('d51.locales'))->mapWithKeys(fn ($l) => [
        $l => $routeName ? route($routeName, [...$route->parameters(), 'locale' => $l]) : url($l),
    ]);
    $nav = [
        ['services.index', route('services.index'), __('site.nav.services'), 'services.*'],
        ['projects.index', route('projects.index'), __('site.nav.projects'), 'projects.*'],
        ['process', route('home').'#process', __('site.nav.process'), null],
        ['about', route('about'), __('site.nav.about'), 'about'],
        ['contact', route('contact'), __('site.nav.contact'), 'contact*'],
    ];
    $overlay = $overlayHeader ?? false;
    $legalName = \App\Models\Setting::localized('legal_name');
    $address = \App\Models\Setting::localized('address');
    $phoneHref = $settings['phone'] ? preg_replace('/[^\d+]/', '', $settings['phone']) : null;
    $socials = collect(['line_url' => 'LINE', 'whatsapp_url' => 'WhatsApp', 'facebook_url' => 'Facebook', 'instagram_url' => 'Instagram'])
        ->filter(fn ($label, $key) => filled($settings[$key]));
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
    <meta name="theme-color" content="#25282a">
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="D51 Construction">
    <meta property="og:title" content="{{ $title }}">
    @if ($description)
        <meta property="og:description" content="{{ $description }}">
    @endif
    <meta property="og:url" content="{{ $alternates[$locale] }}">
    <meta property="og:image" content="{{ asset('images/og-image.jpg') }}">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta name="twitter:card" content="summary_large_image">
    <link rel="icon" href="{{ asset('favicon.ico') }}" sizes="48x48">
    <link rel="icon" href="{{ asset('icon-192.png') }}" type="image/png" sizes="192x192">
    <link rel="apple-touch-icon" href="{{ asset('apple-touch-icon.png') }}">
    {{-- Organization data uses only values the owner has entered in admin. --}}
    <script type="application/ld+json">{!! json_encode(array_filter([
        '@context' => 'https://schema.org',
        '@type' => 'GeneralContractor',
        'name' => 'D51 Construction',
        'alternateName' => $legalName ?: 'ดีไฟว์วัน คอนสตรัคชั่น',
        'url' => url('/'),
        'logo' => asset('icon-512.png'),
        'image' => asset('images/og-image.jpg'),
        'telephone' => $settings['phone'],
        'email' => $settings['email'],
        'address' => $address,
        'areaServed' => \App\Models\Setting::localized('service_area'),
        'sameAs' => array_values(array_filter([$settings['facebook_url'], $settings['instagram_url'], $settings['line_url']])) ?: null,
    ]), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG) !!}</script>
    <meta property="og:locale" content="{{ $locale === 'th' ? 'th_TH' : 'en_US' }}">
    <script>document.documentElement.classList.add('js')</script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('head')
</head>
<body @class(['has-overlay-header' => $overlay])>
    <a class="skip-link" href="#main">{{ __('site.nav.skip') }}</a>

    <header class="site-header" data-header>
        <div class="container site-header__inner">
            <a class="site-header__brand" href="{{ route('home') }}" aria-label="D51 Construction — {{ __('site.nav.home') }}">
                <x-logo />
            </a>

            <button class="menu-toggle" type="button" aria-expanded="false" aria-controls="site-nav" data-menu-toggle>
                <span class="visually-hidden">{{ __('site.nav.menu') }}</span>
                <x-icon name="menu" data-menu-open />
                <x-icon name="close" data-menu-close hidden />
            </button>

            <div class="site-nav" id="site-nav" data-menu>
                <nav aria-label="{{ __('site.nav.main') }}">
                    <ul class="site-nav__links">
                        @foreach ($nav as [$key, $href, $label, $pattern])
                            <li><a href="{{ $href }}" @if ($pattern && request()->routeIs($pattern)) aria-current="page" @endif>{{ $label }}</a></li>
                        @endforeach
                    </ul>
                </nav>
                <nav class="lang-switch" aria-label="{{ __('site.nav.language') }}">
                    @foreach ($alternates as $l => $href)
                        @if (! $loop->first) <span aria-hidden="true">/</span> @endif
                        <a href="{{ $href }}" hreflang="{{ $l }}" lang="{{ $l }}" @if ($l === $locale) aria-current="true" @endif>{{ strtoupper($l) }}</a>
                    @endforeach
                </nav>
                <a class="btn btn--primary" href="{{ route('contact') }}">{{ __('site.nav.cta') }} <x-icon name="arrow" /></a>
            </div>
        </div>
    </header>

    <main id="main">
        @yield('content')
    </main>

    <footer class="site-footer">
        <div class="container site-footer__top">
            <div>
                <x-logo tone="light" />
                <p class="site-footer__about">{{ __('site.footer.about') }}</p>
            </div>

            <nav aria-labelledby="footer-explore">
                <h2 id="footer-explore">{{ __('site.footer.explore') }}</h2>
                <ul class="footer-links">
                    <li><a href="{{ route('home') }}">{{ __('site.nav.home') }}</a></li>
                    @foreach ($nav as [$key, $href, $label])
                        <li><a href="{{ $href }}">{{ $label }}</a></li>
                    @endforeach
                </ul>
            </nav>

            <div>
                <h2>{{ __('site.footer.contact') }}</h2>
                {{-- Only channels with a real value are rendered. --}}
                <ul class="footer-links">
                    @if ($phoneHref)
                        <li><a href="tel:{{ $phoneHref }}"><x-icon name="phone" />{{ $settings['phone'] }}</a></li>
                    @endif
                    @if ($settings['email'])
                        <li><a href="mailto:{{ $settings['email'] }}"><x-icon name="mail" />{{ $settings['email'] }}</a></li>
                    @endif
                    @foreach ($socials as $key => $label)
                        <li><a href="{{ $settings[$key] }}" rel="noopener" target="_blank"><x-icon name="external" />{{ $label }}</a></li>
                    @endforeach
                    @if ($address)
                        <li><span class="footer-links__address">{{ $address }}</span></li>
                    @endif
                    <li><a href="{{ route('contact') }}"><x-icon name="chat" />{{ __('site.footer.contact_form') }}</a></li>
                </ul>
            </div>
        </div>

        <div class="container site-footer__bottom">
            <p>© {{ now()->year }} {{ $legalName ?: 'D51 Construction' }}@if ($settings['registration_no']) · {{ $settings['registration_no'] }}@endif · {{ __('site.footer.rights') }}</p>
            <p><a href="{{ route('privacy') }}">{{ __('site.nav.privacy') }}</a> · d51cons.com</p>
        </div>
    </footer>
</body>
</html>
