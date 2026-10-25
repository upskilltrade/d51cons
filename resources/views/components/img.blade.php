@props(['path', 'alt' => '', 'sizes' => '100vw', 'eager' => false, 'width' => 1536, 'height' => 1024])
@php($src = \App\Support\Media::url($path))
@if ($src)
    <img src="{{ $src }}"
        @if ($srcset = \App\Support\Media::srcset($path)) srcset="{{ $srcset }}" sizes="{{ $sizes }}" @endif
        alt="{{ $alt }}" width="{{ $width }}" height="{{ $height }}"
        @if ($eager) fetchpriority="high" @else loading="lazy" @endif decoding="async"
        {{ $attributes }}>
@endif
