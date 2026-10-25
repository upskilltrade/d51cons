@props(['eyebrow' => null, 'title', 'lead' => null, 'image' => null, 'concept' => false])
<section {{ $attributes->class(['page-hero', 'page-hero--image' => $image]) }}>
    @if ($image)
        <div class="page-hero__media">
            <x-img :path="$image" alt="" eager sizes="100vw" />
        </div>
    @endif
    <div class="container page-hero__inner">
        @if ($eyebrow) <x-eyebrow class="eyebrow--light">{{ $eyebrow }}</x-eyebrow> @endif
        <h1 class="page-hero__title accent-square">{{ $title }}</h1>
        @if ($lead) <p class="page-hero__lead">{{ $lead }}</p> @endif
        {{ $slot }}
    </div>
    @if ($image && $concept)
        <x-concept-label class="concept-label--corner" />
    @endif
</section>
