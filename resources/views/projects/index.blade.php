@extends('layouts.site', ['title' => 'Projects'])

@section('content')
    <h1>Projects</h1>

    <nav aria-label="Filter">
        <a href="{{ route('projects.index') }}" @if (! $category) aria-current="true" @endif>All</a>
        @foreach ($categories as $item)
            <a href="{{ route('projects.index', ['category' => $item->slug]) }}" @if ($category?->is($item)) aria-current="true" @endif>{{ $item->localized('title') }}</a>
        @endforeach
    </nav>

    @forelse ($projects as $project)
        <x-project-card :project="$project" />
    @empty
        {{-- Empty state: no fake case studies. --}}
        <p><a href="{{ route('contact') }}">Contact</a></p>
    @endforelse

    {{ $projects->links() }}

    @if ($concepts->isNotEmpty())
        <section aria-label="Design inspiration">
            @foreach ($concepts as $project)
                <x-project-card :project="$project" />
            @endforeach
        </section>
    @endif
@endsection
