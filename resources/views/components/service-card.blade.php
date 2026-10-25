{{-- $service is a Service model, or an array from site.default_services before services are published. --}}
@props(['service', 'number', 'image' => null, 'href'])
@php
    $isModel = $service instanceof \App\Models\Service;
    $title = $isModel ? $service->localized('title') : $service['title'];
    $summary = $isModel ? $service->localized('summary') : $service['summary'];
    $image = ($isModel ? $service->image_path : null) ?: $image;
@endphp
<article {{ $attributes->class('service-card') }}>
    <a class="service-card__link" href="{{ $href }}">
        <div class="service-card__media">
            @if ($image)
                <x-img :path="$image" alt="" sizes="(min-width: 1024px) 25vw, (min-width: 640px) 50vw, 100vw" />
                @if (\Illuminate\Support\Str::startsWith($image, 'images/concepts/'))
                    <x-concept-label class="concept-label--corner" />
                @endif
            @endif
        </div>
        <div class="service-card__body">
            <span class="service-card__number">{{ str_pad($number, 2, '0', STR_PAD_LEFT) }}</span>
            <h3 class="service-card__title">{{ $title }}</h3>
            @if ($summary) <p class="service-card__summary">{{ $summary }}</p> @endif
            <span class="service-card__more"><x-icon name="arrow" /><span class="service-card__rule"></span></span>
        </div>
    </a>
</article>
