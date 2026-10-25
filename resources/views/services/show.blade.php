@extends('layouts.site', ['title' => $service->localized('title'), 'description' => $service->localized('summary')])

@section('content')
    <x-page-hero :eyebrow="__('site.services.eyebrow')" :title="$service->localized('title')" :lead="$service->localized('summary')"
        :image="$service->image_path" :concept="\Illuminate\Support\Str::startsWith((string) $service->image_path, 'images/concepts/')">
    </x-page-hero>

    <section class="section">
        <div class="container detail">
            <div>
                <a class="text-link" href="{{ route('services.index') }}"><x-icon name="arrow-left" /> {{ __('site.services.back') }}</a>
                @if ($body = $service->localized('body'))
                    <div class="prose" style="margin-top: 40px">
                        @foreach (preg_split('/\R{2,}/', trim($body)) as $paragraph)
                            <p>{!! nl2br(e($paragraph)) !!}</p>
                        @endforeach
                    </div>
                @endif
            </div>
            <aside class="aside-card" aria-labelledby="consult-title">
                <h2 id="consult-title">{{ __('site.services.consult_title') }}</h2>
                <p>{{ __('site.services.consult_text') }}</p>
                <a class="btn btn--primary btn--block" href="{{ route('contact', ['service' => $service->slug]) }}">{{ __('site.services.consult') }} <x-icon name="arrow" /></a>
            </aside>
        </div>
    </section>

    @if ($projects->isNotEmpty())
        <section class="section section--dark" aria-labelledby="related-title">
            <div class="container">
                <div class="section-head">
                    <h2 id="related-title" class="display accent-square">{{ __('site.services.related') }}</h2>
                </div>
                <div class="project-grid reveal">
                    @foreach ($projects as $project)
                        <x-project-card :project="$project" />
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <x-cta-band :service="$service->slug" />
@endsection
