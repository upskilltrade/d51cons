@extends('layouts.site', ['title' => $service->localized('title'), 'description' => $service->localized('summary')])

@section('content')
    <h1>{{ $service->localized('title') }}</h1>
    @if ($service->imageUrl())
        <img src="{{ $service->imageUrl() }}" alt="{{ $service->localized('title') }}">
    @endif
    {!! nl2br(e($service->localized('body'))) !!}

    @foreach ($projects as $project)
        <x-project-card :project="$project" />
    @endforeach

    <a href="{{ route('contact', ['service' => $service->slug]) }}">Contact</a>
@endsection
