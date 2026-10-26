@extends('layouts.site', ['title' => __('site.projects.title')])

@section('content')
    <x-page-hero :eyebrow="__('site.projects.eyebrow')" :title="__('site.projects.heading')" :lead="__('site.projects.lead')"
        image="images/concepts/detail-materials.webp" concept />

    <section class="section">
        <div class="container">
            @if ($categories->isNotEmpty())
                <nav class="filter" aria-label="{{ __('site.projects.filter') }}">
                    <a href="{{ route('projects.index') }}" @if (! $category) aria-current="true" @endif>{{ __('site.projects.all') }}</a>
                    @foreach ($categories as $item)
                        <a href="{{ route('projects.index', ['category' => $item->slug]) }}" @if ($category?->is($item)) aria-current="true" @endif>{{ $item->localized('title') }}</a>
                    @endforeach
                </nav>
            @endif

            @if ($projects->isNotEmpty())
                <div class="project-grid reveal">
                    @foreach ($projects as $project)
                        <x-project-card :project="$project" />
                    @endforeach
                </div>
                {{ $projects->links('partials.pagination') }}
            @else
                {{-- Empty state: no fake case studies. --}}
                <div class="empty-state reveal">
                    <div>
                        <h2 class="accent-square">{{ __('site.projects.empty_title') }}</h2>
                        <p>{{ __('site.projects.empty_text') }}</p>
                    </div>
                    <a class="btn btn--primary" href="{{ route('contact') }}">{{ __('site.nav.cta') }} <x-icon name="arrow" /></a>
                </div>
            @endif
        </div>
    </section>

    @if ($concepts->isNotEmpty())
        <section class="section section--dark" aria-labelledby="inspiration-title">
            <div class="container">
                <div class="section-head section-head--split reveal">
                    <div>
                        <x-eyebrow class="eyebrow--light">{{ __('site.projects.inspiration_eyebrow') }}</x-eyebrow>
                        <h2 id="inspiration-title" class="display accent-square">{{ __('site.projects.inspiration_title') }}</h2>
                    </div>
                    <div class="section-head__aside">
                        <p>{{ __('site.projects.inspiration_text') }}</p>
                    </div>
                </div>
                <div class="project-grid reveal">
                    @foreach ($concepts as $project)
                        <x-project-card :project="$project" />
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <x-cta-band />
@endsection
