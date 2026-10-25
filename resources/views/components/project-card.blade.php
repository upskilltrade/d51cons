@props(['project', 'size' => 'default', 'sizes' => '(min-width: 1024px) 33vw, (min-width: 640px) 50vw, 100vw'])
<article {{ $attributes->class(['project-card', 'project-card--'.$size]) }}>
    <a class="project-card__link" href="{{ route('projects.show', $project) }}">
        <div class="project-card__media">
            @if ($project->coverUrl())
                <x-img :path="$project->cover_path" :alt="$project->localized('title')" :sizes="$sizes" />
            @endif
        </div>
        <div class="project-card__caption">
            <div>
                @if ($project->service)
                    <span class="project-card__type">{{ $project->service->localized('title') }}</span>
                @endif
                <h3 class="project-card__title">{{ $project->localized('title') }}</h3>
            </div>
            @if ($project->is_concept)
                <x-concept-label />
            @else
                <span class="status-tag">{{ $project->status->label() }}</span>
            @endif
        </div>
    </a>
</article>
