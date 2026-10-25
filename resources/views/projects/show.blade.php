@extends('layouts.site', ['title' => $project->localized('title'), 'description' => $project->localized('summary')])

@section('content')
    <h1>{{ $project->localized('title') }}</h1>
    @if ($project->is_concept)
        <x-concept-label />
    @else
        <p>{{ $project->status->label() }}</p>
    @endif

    <dl>
        @if ($project->service)
            <dt>Type</dt><dd>{{ $project->service->localized('title') }}</dd>
        @endif
        @if ($project->location)
            <dt>Location</dt><dd>{{ $project->location }}</dd>
        @endif
        @if ($project->area_sqm)
            <dt>Area</dt><dd>{{ number_format($project->area_sqm) }} m²</dd>
        @endif
    </dl>

    @if ($project->coverUrl())
        <img src="{{ $project->coverUrl() }}" alt="{{ $project->localized('title') }}">
    @endif
    {!! nl2br(e($project->localized('body'))) !!}

    @foreach ($project->images as $image)
        <figure>
            <img src="{{ $image->url() }}" alt="{{ $image->localized('alt') ?? $project->localized('title') }}" loading="lazy">
            @if ($caption = $image->localized('caption'))
                <figcaption>{{ $caption }}</figcaption>
            @endif
        </figure>
    @endforeach

    <a href="{{ route('contact') }}">Contact</a>
@endsection
