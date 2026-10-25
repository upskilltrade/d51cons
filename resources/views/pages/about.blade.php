@extends('layouts.site', ['title' => __('site.about.title')])

@section('content')
    <x-page-hero :eyebrow="__('site.about.eyebrow')" :title="__('site.about.heading')" :lead="__('site.about.lead')"
        image="images/concepts/commercial-building.webp" concept />

    @if ($about = \App\Models\Setting::localized('about'))
        <section class="section section--paper">
            <div class="container">
                <div class="prose prose--lead reveal">
                    @foreach (preg_split('/\R{2,}/', trim($about)) as $paragraph)
                        <p>{!! nl2br(e($paragraph)) !!}</p>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <section class="section" aria-labelledby="values-title">
        <div class="container">
            <div class="section-head reveal">
                <x-eyebrow>Modern Precision</x-eyebrow>
                <h2 id="values-title" class="display accent-square">{{ __('site.about.values_title') }}</h2>
            </div>
            <ul class="values reveal">
                @foreach (__('site.about.values') as $value)
                    <li class="value">
                        <span class="value__number">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                        <h3>{{ $value['title'] }}</h3>
                        <p>{{ $value['text'] }}</p>
                    </li>
                @endforeach
            </ul>
        </div>
    </section>

    <section class="section section--paper" aria-labelledby="process-title">
        <div class="container">
            <div class="section-head reveal">
                <x-eyebrow>{{ __('site.home.process_eyebrow') }}</x-eyebrow>
                <h2 id="process-title" class="display accent-square">{{ __('site.home.process_title') }}</h2>
            </div>
            <ol class="process reveal">
                @foreach (__('site.home.steps') as $step)
                    <li class="process__step">
                        <span class="process__number">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                        <h3>{{ $step['title'] }}</h3>
                        <p>{{ $step['text'] }}</p>
                    </li>
                @endforeach
            </ol>
        </div>
    </section>

    <x-cta-band />
@endsection
