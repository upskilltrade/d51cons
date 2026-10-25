@extends('layouts.site', ['title' => __('site.contact.title')])

@section('content')
    <section class="section section--paper">
        <div class="container thanks">
            <div class="thanks__icon"><x-icon name="check" /></div>
            <h1>{{ __('site.contact.thanks_title') }}</h1>
            <p role="status">{{ __('site.contact.thanks') }}</p>
            <a class="btn btn--outline" href="{{ route('home') }}">{{ __('site.contact.back_home') }} <x-icon name="arrow" /></a>
        </div>
    </section>
@endsection
