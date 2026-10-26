@extends('layouts.site', ['title' => __('site.contact.title')])

@php
    $settings = \App\Models\Setting::allValues();
    $area = \App\Models\Setting::localized('service_area');
    $address = \App\Models\Setting::localized('address');
    $label = fn (string $field) => \Illuminate\Support\Str::ucfirst(__('site.contact.fields.'.$field));
    $socials = collect(['line_url' => 'LINE', 'whatsapp_url' => 'WhatsApp', 'facebook_url' => 'Facebook', 'instagram_url' => 'Instagram'])
        ->filter(fn ($name, $key) => filled($settings[$key]));
    $hasChannels = $settings['phone'] || $settings['email'] || $socials->isNotEmpty();
@endphp

@section('content')
    <x-page-hero :eyebrow="__('site.contact.eyebrow')" :title="__('site.contact.heading')" :lead="__('site.contact.lead')"
        image="images/concepts/detail-materials.webp" concept />

    <section class="section">
        <div class="container contact-layout">
            <div class="contact-info">
                @if ($hasChannels)
                    <div>
                        <h2>{{ __('site.contact.channels') }}</h2>
                        <ul class="channel-list">
                            @if ($settings['phone'])
                                <li><a class="channel" href="tel:{{ preg_replace('/[^\d+]/', '', $settings['phone']) }}"><x-icon name="phone" />{{ $settings['phone'] }}</a></li>
                            @endif
                            @if ($settings['email'])
                                <li><a class="channel" href="mailto:{{ $settings['email'] }}"><x-icon name="mail" />{{ $settings['email'] }}</a></li>
                            @endif
                            @foreach ($socials as $key => $name)
                                <li><a class="channel" href="{{ $settings[$key] }}" rel="noopener" target="_blank"><x-icon name="external" />{{ $name }}</a></li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if ($area)
                    <div>
                        <h2>{{ __('site.contact.area') }}</h2>
                        <p class="lead">{{ $area }}</p>
                    </div>
                @endif

                @if ($address)
                    <div>
                        <h2>{{ __('site.contact.address') }}</h2>
                        <p class="lead">{!! nl2br(e($address)) !!}</p>
                        @if ($settings['map_url'])
                            <p style="margin-top: 12px"><a class="text-link text-link--brick" href="{{ $settings['map_url'] }}" rel="noopener" target="_blank">{{ __('site.contact.map') }} <x-icon name="arrow" /></a></p>
                        @endif
                    </div>
                @endif

                <div>
                    <h2>{{ __('site.contact.next_title') }}</h2>
                    <ol class="steps-list">
                        @foreach (__('site.contact.next') as $step)
                            <li>{{ $step }}</li>
                        @endforeach
                    </ol>
                </div>
            </div>

            <div class="form-card">
                <h2 class="accent-square">{{ __('site.contact.form_title') }}</h2>

                @if ($errors->any())
                    <div class="alert" role="alert"><x-icon name="alert" />{{ __('site.contact.error_summary') }}</div>
                @endif

                <form method="POST" action="{{ route('contact.store') }}" novalidate>
                    @csrf

                    <div class="form-grid">
                        @foreach (['name' => ['text', 'name'], 'email' => ['email', 'email'], 'phone' => ['tel', 'tel'], 'location' => ['text', 'off']] as $field => [$type, $autocomplete])
                            @php($required = in_array($field, ['name', 'email']))
                            <div @class(['field', 'field--invalid' => $errors->has($field)])>
                                <label for="{{ $field }}">
                                    {{ $label($field) }}
                                    <span class="field__hint">{{ $required ? '*' : __('site.contact.optional') }}</span>
                                </label>
                                <input id="{{ $field }}" name="{{ $field }}" type="{{ $type }}" value="{{ old($field) }}" autocomplete="{{ $autocomplete }}"
                                    @if ($required) required aria-required="true" @endif
                                    @error($field) aria-invalid="true" aria-describedby="{{ $field }}-error" @enderror>
                                @error($field) <p class="field__error" id="{{ $field }}-error">{{ $message }}</p> @enderror
                            </div>
                        @endforeach

                        <div @class(['field', 'field--invalid' => $errors->has('service_type')])>
                            <label for="service_type">{{ $label('service_type') }}</label>
                            <select id="service_type" name="service_type" @error('service_type') aria-invalid="true" aria-describedby="service_type-error" @enderror>
                                <option value="">{{ __('site.contact.choose') }}</option>
                                @foreach ($services as $service)
                                    <option value="{{ $service->slug }}" @selected(old('service_type', request('service')) === $service->slug)>{{ $service->localized('title') }}</option>
                                @endforeach
                                <option value="other" @selected(old('service_type') === 'other')>{{ __('site.contact.other_service') }}</option>
                            </select>
                            @error('service_type') <p class="field__error" id="service_type-error">{{ $message }}</p> @enderror
                        </div>

                        <div @class(['field', 'field--invalid' => $errors->has('budget_range')])>
                            <label for="budget_range">{{ $label('budget_range') }} <span class="field__hint">{{ __('site.contact.optional') }}</span></label>
                            <select id="budget_range" name="budget_range" @error('budget_range') aria-invalid="true" aria-describedby="budget_range-error" @enderror>
                                <option value="">{{ __('site.contact.choose') }}</option>
                                @foreach ($budgetRanges as $range)
                                    <option value="{{ $range }}" @selected(old('budget_range') === $range)>{{ __('site.budget_ranges.'.$range) }}</option>
                                @endforeach
                            </select>
                            @error('budget_range') <p class="field__error" id="budget_range-error">{{ $message }}</p> @enderror
                        </div>

                        <div @class(['field', 'field--full', 'field--invalid' => $errors->has('message')])>
                            <label for="message">{{ $label('message') }} <span class="field__hint">*</span></label>
                            <textarea id="message" name="message" required aria-required="true" @error('message') aria-invalid="true" aria-describedby="message-error" @enderror>{{ old('message') }}</textarea>
                            @error('message') <p class="field__error" id="message-error">{{ $message }}</p> @enderror
                        </div>

                        <div class="field field--full">
                            <label class="check" for="privacy">
                                <input id="privacy" type="checkbox" name="privacy" value="1" @checked(old('privacy')) required aria-required="true">
                                <span>{{ __('site.contact.privacy_text') }} <a href="{{ route('privacy') }}" target="_blank">{{ __('site.contact.fields.privacy') }}</a> *</span>
                            </label>
                            @error('privacy') <p class="field__error">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    {{-- Honeypot: keep hidden from people and assistive tech. --}}
                    <div hidden aria-hidden="true">
                        <label for="website">Website</label>
                        <input id="website" name="website" type="text" tabindex="-1" autocomplete="off">
                    </div>

                    <div class="form-actions">
                        <button class="btn btn--primary btn--block" type="submit">{{ __('site.contact.send') }} <x-icon name="arrow" /></button>
                    </div>
                </form>
            </div>
        </div>
    </section>
@endsection
