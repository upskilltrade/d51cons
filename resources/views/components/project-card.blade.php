@props(['project'])
<article>
    <a href="{{ route('projects.show', $project) }}">
        @if ($project->coverUrl())
            <img src="{{ $project->coverUrl() }}" alt="{{ $project->localized('title') }}" loading="lazy">
        @endif
        <h3>{{ $project->localized('title') }}</h3>
    </a>
    @if ($project->is_concept)
        <x-concept-label />
    @else
        <p>{{ $project->status->label() }}</p>
    @endif
</article>
