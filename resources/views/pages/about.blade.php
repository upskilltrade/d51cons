@extends('layouts.site', ['title' => 'About'])

@section('content')
    <h1>About</h1>
    @if ($about = \App\Models\Setting::localized('about'))
        {!! nl2br(e($about)) !!}
    @endif
@endsection
