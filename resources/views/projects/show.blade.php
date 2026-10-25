@extends('layouts.site', ['title' => $project->localized('title'), 'description' => $project->localized('summary')])

@section('content')
    <x-page-hero :eyebrow="$project->is_concept ? __('site.projects.inspiration_eyebrow') : __('site.projects.eyebrow')"
        :title="$project->localized('title')" :lead="$project->localized('summary')"
        :image="$project->cover_path" :concept="$project->is_concept" />

    <section class="section">
        <div class="container">
            <a class="text-link" href="{{ route('projects.index') }}"><x-icon name="arrow-left" /> {{ __('site.projects.back') }}</a>

            <dl class="specs" style="margin-top: 40px">
                <div>
                    <dt>{{ __('site.projects.status') }}</dt>
                    <dd>{{ $project->is_concept ? __('site.concept_label') : $project->status->label() }}</dd>
                </div>
                @if ($project->service)
                    <div><dt>{{ __('site.projects.type') }}</dt><dd>{{ $project->service->localized('title') }}</dd></div>
                @endif
                @if ($project->location)
                    <div><dt>{{ __('site.projects.location') }}</dt><dd>{{ $project->location }}</dd></div>
                @endif
                @if ($project->area_sqm)
                    <div><dt>{{ __('site.projects.area') }}</dt><dd>{{ number_format($project->area_sqm) }} m²</dd></div>
                @endif
            </dl>

            @if ($project->is_concept)
                <p class="notice" style="margin: 0 0 48px">{{ __('site.projects.inspiration_text') }}</p>
            @endif

            @if ($project->images->isEmpty() && $project->cover_path)
                <figure class="feature-image reveal">
                    <x-img :path="$project->cover_path" :alt="$project->localized('title')" sizes="(min-width: 1320px) 1240px, 100vw" />
                    @if ($project->is_concept) <x-concept-label class="concept-label--corner" /> @endif
                </figure>
            @endif

            @if ($body = $project->localized('body'))
                <div class="prose">
                    @foreach (preg_split('/\R{2,}/', trim($body)) as $paragraph)
                        <p>{!! nl2br(e($paragraph)) !!}</p>
                    @endforeach
                </div>
            @endif

            @if ($project->images->isNotEmpty())
                <h2 class="visually-hidden">{{ __('site.projects.gallery') }}</h2>
                <div class="gallery">
                    @foreach ($project->images as $image)
                        <figure class="reveal">
                            <img src="{{ $image->url() }}" alt="{{ $image->localized('alt') ?? $project->localized('title') }}" loading="lazy" decoding="async">
                            @if ($caption = $image->localized('caption'))
                                <figcaption>{{ $caption }}</figcaption>
                            @endif
                        </figure>
                    @endforeach
                </div>
            @endif
        </div>
    </section>

    <x-cta-band :title="__('site.projects.cta_title')" :service="$project->service?->slug" />
@endsection
