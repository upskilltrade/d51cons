{{-- Usage: @include('admin.field', ['name' => 'title_th', 'label' => '...', 'value' => ..., 'type' => 'text|textarea|number|url|email|datetime-local']) --}}
@php($type ??= 'text')
<label for="{{ $name }}">{{ $label }}</label>
@if ($type === 'textarea')
    <textarea id="{{ $name }}" name="{{ $name }}">{{ old($name, $value) }}</textarea>
@else
    <input id="{{ $name }}" name="{{ $name }}" type="{{ $type }}" value="{{ old($name, $value) }}">
@endif
@error($name) <p class="error">{{ $message }}</p> @enderror
