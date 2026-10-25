@extends('layouts.site', ['title' => 'Contact'])

@section('content')
    <h1>Contact</h1>

    @if ($area = \App\Models\Setting::localized('service_area'))
        <p>{{ $area }}</p>
    @endif

    <form method="POST" action="{{ route('contact.store') }}" novalidate>
        @csrf

        @foreach (['name' => 'text', 'email' => 'email', 'phone' => 'tel', 'location' => 'text'] as $field => $type)
            <label for="{{ $field }}">{{ __('site.contact.fields.'.$field) }}</label>
            <input id="{{ $field }}" name="{{ $field }}" type="{{ $type }}" value="{{ old($field) }}" @if (in_array($field, ['name', 'email'])) required @endif>
            @error($field) <p role="alert">{{ $message }}</p> @enderror
        @endforeach

        <label for="service_type">{{ __('site.contact.fields.service_type') }}</label>
        <select id="service_type" name="service_type">
            <option value=""></option>
            @foreach ($services as $service)
                <option value="{{ $service->slug }}" @selected(old('service_type', request('service')) === $service->slug)>{{ $service->localized('title') }}</option>
            @endforeach
            <option value="other" @selected(old('service_type') === 'other')>{{ __('site.contact.other_service') }}</option>
        </select>
        @error('service_type') <p role="alert">{{ $message }}</p> @enderror

        <label for="budget_range">{{ __('site.contact.fields.budget_range') }}</label>
        <select id="budget_range" name="budget_range">
            <option value=""></option>
            @foreach ($budgetRanges as $range)
                <option value="{{ $range }}" @selected(old('budget_range') === $range)>{{ __('site.budget_ranges.'.$range) }}</option>
            @endforeach
        </select>
        @error('budget_range') <p role="alert">{{ $message }}</p> @enderror

        <label for="message">{{ __('site.contact.fields.message') }}</label>
        <textarea id="message" name="message" required>{{ old('message') }}</textarea>
        @error('message') <p role="alert">{{ $message }}</p> @enderror

        <label>
            <input type="checkbox" name="privacy" value="1" @checked(old('privacy')) required>
            <a href="{{ route('privacy') }}">{{ __('site.contact.fields.privacy') }}</a>
        </label>
        @error('privacy') <p role="alert">{{ $message }}</p> @enderror

        {{-- Honeypot: keep hidden from people and assistive tech. --}}
        <div hidden aria-hidden="true">
            <label for="website">Website</label>
            <input id="website" name="website" type="text" tabindex="-1" autocomplete="off">
        </div>

        <button type="submit">Send</button>
    </form>
@endsection
