@extends('layouts.site')

@section('content')
    <section aria-label="Hero">
        <h1>{{ app()->getLocale() === 'th' ? 'ใส่ใจทุกรายละเอียด ตั้งแต่แบบถึงงานสร้าง' : 'Built with precision.' }}</h1>
        <a href="{{ route('contact') }}">Contact</a>
        <a href="{{ route('services.index') }}">Services</a>
    </section>

    <section aria-label="Services">
        @foreach ($services as $service)
            <article>
                <a href="{{ route('services.show', $service) }}">
                    <h2>{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }} {{ $service->localized('title') }}</h2>
                </a>
                <p>{{ $service->localized('summary') }}</p>
            </article>
        @endforeach
    </section>

    @if ($projects->isNotEmpty())
        <section aria-label="Projects">
            @foreach ($projects as $project)
                <x-project-card :project="$project" />
            @endforeach
        </section>
    @endif

    @if ($concepts->isNotEmpty())
        <section aria-label="Design inspiration">
            @foreach ($concepts as $project)
                <x-project-card :project="$project" />
            @endforeach
        </section>
    @endif
@endsection
