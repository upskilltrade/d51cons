@props(['title' => null, 'service' => null])
<section class="cta-band" aria-labelledby="cta-title">
    <div class="container cta-band__inner">
        <div class="cta-band__copy reveal">
            <x-eyebrow class="eyebrow--light">{{ __('site.cta.eyebrow') }}</x-eyebrow>
            <h2 id="cta-title" class="cta-band__title accent-square">{{ $title ?? __('site.cta.title') }}</h2>
            <p class="cta-band__text">{{ __('site.cta.text') }}</p>
            <a class="btn btn--primary" href="{{ route('contact', array_filter(['service' => $service])) }}">
                {{ __('site.cta.button') }} <x-icon name="arrow" />
            </a>
        </div>
        <div class="cta-band__slats" aria-hidden="true"></div>
    </div>
</section>
