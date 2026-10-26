@extends('layouts.site', ['title' => __('site.services.title')])

@section('content')
    @php
        $fallbackImages = ['images/concepts/service-custom-home.webp', 'images/concepts/service-pool-villa.webp', 'images/concepts/commercial-building.webp', 'images/concepts/service-renovation.webp'];
        $items = $services->isNotEmpty() ? $services : collect(__('site.default_services'));
        // The first four lines get full rows; the rest are shown as a compact grid.
        $more = $items->slice(4);
        $items = $items->take(4);
    @endphp

    <x-page-hero :eyebrow="__('site.services.eyebrow')" :title="__('site.services.heading')" :lead="__('site.services.lead')"
        image="images/concepts/detail-materials.webp" concept />

    <section class="section">
        <div class="container service-list">
            @foreach ($items as $service)
                @php
                    $isModel = $service instanceof \App\Models\Service;
                    $href = $isModel ? route('services.show', $service) : route('contact');
                    $image = ($isModel ? $service->image_path : null) ?: $fallbackImages[$loop->index % 4];
                    $title = $isModel ? $service->localized('title') : $service['title'];
                    $summary = $isModel ? $service->localized('summary') : $service['summary'];
                @endphp
                <article class="service-row reveal">
                    <a class="service-row__media" href="{{ $href }}" tabindex="-1" aria-hidden="true">
                        <x-img :path="$image" alt="" sizes="(min-width: 900px) 55vw, 100vw" />
                        @if (\Illuminate\Support\Str::startsWith($image, 'images/concepts/'))
                            <x-concept-label class="concept-label--corner" />
                        @endif
                    </a>
                    <div>
                        <span class="service-row__number">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                        <h2><a href="{{ $href }}">{{ $title }}</a></h2>
                        @if ($summary) <p>{{ $summary }}</p> @endif
                        <a class="text-link text-link--brick" href="{{ $href }}">
                            {{ $isModel ? __('site.services.more') : __('site.services.consult') }} <x-icon name="arrow" />
                        </a>
                    </div>
                </article>
            @endforeach
        </div>
    </section>

    @if ($more->isNotEmpty())
        <section class="section section--paper" aria-labelledby="more-services-title">
            <div class="container">
                <div class="section-head section-head--split reveal">
                    <div>
                        <x-eyebrow>{{ __('site.services.more_eyebrow') }}</x-eyebrow>
                        <h2 id="more-services-title" class="display accent-square">{{ __('site.services.more_title') }}</h2>
                    </div>
                    <div class="section-head__aside">
                        <p class="lead">{{ __('site.services.more_text') }}</p>
                    </div>
                </div>
                <div class="service-grid reveal">
                    @foreach ($more as $service)
                        <x-service-card :service="$service" :number="$loop->iteration + 4" :href="route('services.show', $service)" />
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <x-cta-band />
@endsection
