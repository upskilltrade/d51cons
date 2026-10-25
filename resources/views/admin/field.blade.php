{{-- Usage: @include('admin.field', ['name' => 'title_th', 'label' => '...', 'value' => ..., 'type' => 'text|textarea|number|url|email|datetime-local', 'help' => '...', 'class' => 'span-2']) --}}
@php($type ??= 'text')
<div @class(['field', $class ?? null, 'field--invalid' => $errors->has($name)])>
    <label for="{{ $name }}">{{ $label }}</label>
    @if ($type === 'textarea')
        <textarea id="{{ $name }}" name="{{ $name }}">{{ old($name, $value) }}</textarea>
    @else
        <input id="{{ $name }}" name="{{ $name }}" type="{{ $type }}" value="{{ old($name, $value) }}">
    @endif
    @if (! empty($help)) <p class="field__help">{{ $help }}</p> @endif
    @error($name) <p class="error">{{ $message }}</p> @enderror
</div>
