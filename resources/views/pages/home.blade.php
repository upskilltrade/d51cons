@extends('layouts.site', ['title' => __('site.home.title'), 'overlayHeader' => true])

@section('content')
    @php($showcase = $projects->isNotEmpty() ? $projects : $concepts)

    <section class="hero" aria-labelledby="hero-title">
        <div class="hero__media">
            <x-img path="images/concepts/hero-pool-villa.webp" :alt="app()->getLocale() === 'th' ? 'ภาพแนวคิดพูลวิลล่าสไตล์โมเดิร์นทรอปิคอล' : 'Concept image of a modern tropical pool villa'" eager />
        </div>
        <div class="container hero__inner">
            <div class="hero__content">
                <x-eyebrow class="eyebrow--light">{{ __('site.home.hero_eyebrow') }}</x-eyebrow>
                <h1 id="hero-title" class="hero__title">
                    @foreach (__('site.home.hero_title') as $line)<span @class(['accent-square' => $loop->last])>{{ $line }}</span>@endforeach
                </h1>
                <p class="hero__lead">{{ __('site.home.hero_lead') }}</p>
                <div class="hero__actions">
                    <a class="btn btn--primary" href="{{ route('contact') }}">{{ __('site.home.hero_primary') }} <x-icon name="arrow" /></a>
                    <a class="btn btn--ghost" href="{{ route('services.index') }}">{{ __('site.home.hero_secondary') }} <x-icon name="arrow" /></a>
                </div>
            </div>
            <div class="hero__strip">
                <span>D51 Construction &nbsp;/&nbsp; {{ __('site.brand.scope') }}</span>
                <x-concept-label />
            </div>
        </div>
    </section>

    <section class="section section--paper" aria-labelledby="intro-title">
        <div class="container intro">
            <div class="reveal">
                <x-eyebrow>{{ __('site.home.intro_eyebrow') }}</x-eyebrow>
                <h2 id="intro-title" class="display accent-square">{{ __('site.home.intro_title') }}</h2>
                <p class="lead intro__text">{{ __('site.home.intro_text') }}</p>
            </div>
            <ul class="points intro__points reveal">
                @foreach (__('site.home.points') as $i => $point)
                    <li class="point">
                        <span class="point__icon"><x-icon :name="['scope', 'build', 'progress'][$i]" /></span>
                        <div>
                            <h3>{{ $point['title'] }}</h3>
                            <p>{{ $point['text'] }}</p>
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>
    </section>

    <section class="section" aria-labelledby="services-title">
        <div class="container">
            <div class="section-head section-head--split reveal">
                <div>
                    <x-eyebrow>{{ __('site.home.services_eyebrow') }}</x-eyebrow>
                    <h2 id="services-title" class="display accent-square">{{ __('site.home.services_title') }}</h2>
                </div>
                <div class="section-head__aside">
                    <p class="lead">{{ __('site.brand.scope') }}</p>
                    <a class="text-link text-link--brick" href="{{ route('services.index') }}">{{ __('site.home.services_all') }} <x-icon name="arrow" /></a>
                </div>
            </div>
            @include('partials.service-grid')
        </div>
    </section>

    @if ($showcase->isNotEmpty())
        <section class="section section--dark" aria-labelledby="projects-title">
            <div class="container">
                <div class="section-head section-head--split reveal">
                    <div>
                        <x-eyebrow class="eyebrow--light">{{ __('site.home.projects_eyebrow') }}</x-eyebrow>
                        <h2 id="projects-title" class="display accent-square">{{ __('site.home.projects_title') }}</h2>
                    </div>
                    <div class="section-head__aside">
                        <p>{{ __('site.home.projects_text') }}</p>
                        <a class="text-link" href="{{ route('projects.index') }}">{{ __('site.home.projects_all') }} <x-icon name="arrow" /></a>
                    </div>
                </div>
                <div class="project-grid project-grid--editorial reveal">
                    @foreach ($showcase as $project)
                        <x-project-card :project="$project" :size="$loop->first ? 'large' : 'default'"
                            :sizes="$loop->first ? '(min-width: 900px) 62vw, 100vw' : '(min-width: 900px) 38vw, 100vw'" />
                    @endforeach
                </div>
                @if ($projects->isEmpty())
                    <p class="notice">{{ __('site.concept_note') }}</p>
                @endif
            </div>
        </section>
    @endif

    <section class="section section--paper" id="process" aria-labelledby="process-title">
        <div class="container">
            <div class="section-head section-head--split reveal">
                <div>
                    <x-eyebrow>{{ __('site.home.process_eyebrow') }}</x-eyebrow>
                    <h2 id="process-title" class="display accent-square">{{ __('site.home.process_title') }}</h2>
                </div>
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

    <section class="split" aria-labelledby="quality-title">
        <div class="split__media">
            <x-img path="images/concepts/construction-quality.webp" :alt="app()->getLocale() === 'th' ? 'ภาพจำลองการตรวจวัดงานก่อสร้าง' : 'Concept image of on-site construction measurement'" sizes="(min-width: 960px) 54vw, 100vw" />
            <x-concept-label class="concept-label--corner" />
        </div>
        <div class="split__content reveal">
            <x-eyebrow>{{ __('site.home.quality_eyebrow') }}</x-eyebrow>
            <h2 id="quality-title" class="accent-square">{{ __('site.home.quality_title') }}</h2>
            <p class="lead">{{ __('site.home.quality_text') }}</p>
            <ul class="checklist">
                @foreach (__('site.home.quality_points') as $point)
                    <li><x-icon name="check" />{{ $point }}</li>
                @endforeach
            </ul>
            <a class="btn btn--outline" href="#process">{{ __('site.home.quality_cta') }} <x-icon name="arrow" /></a>
        </div>
    </section>

    <x-cta-band />
@endsection
