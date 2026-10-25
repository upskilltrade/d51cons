@extends('layouts.site', ['title' => 'Services'])

@section('content')
    <h1>Services</h1>
    @foreach ($services as $service)
        <article>
            <h2><a href="{{ route('services.show', $service) }}">{{ $service->localized('title') }}</a></h2>
            <p>{{ $service->localized('summary') }}</p>
        </article>
    @endforeach
@endsection
