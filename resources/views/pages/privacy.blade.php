@extends('layouts.site', ['title' => __('site.privacy.title')])

@section('content')
    <x-page-hero :eyebrow="__('site.privacy.eyebrow')" :title="__('site.privacy.title')" />

    <section class="section">
        <div class="container legal">
            {{-- Draft notice based on what the site actually collects; owner to confirm before launch. --}}
            @foreach (__('site.privacy.sections') as $section)
                <section>
                    <h2>{{ $section['title'] }}</h2>
                    <p>{{ $section['text'] }}</p>
                </section>
            @endforeach
        </div>
    </section>
@endsection
