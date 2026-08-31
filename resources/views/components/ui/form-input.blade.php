@props([
    'type' => 'text',
    'name',
    'id' => null,
    'label' => null,
    'value' => '',
    'placeholder' => '',
    'required' => false,
    'options' => [], // for select
    'rows' => 3 // for textarea
])

@php
    $id = $id ?? $name;
@endphp

<div class="mb-4">
    @if($label)
        <label for="{{ $id }}" class="form-label">
            {{ $label }}
            @if($required)
                <span class="text-red-500">*</span>
            @endif
        </label>
    @endif

    @if($type === 'select')
        <select 
            name="{{ $name }}" 
            id="{{ $id }}"
            {{ $required ? 'required' : '' }}
            {{ $attributes->merge(['class' => 'form-input']) }}
        >
            <option value="" disabled {{ empty($value) ? 'selected' : '' }}>-- Select Option --</option>
            @foreach($options as $val => $text)
                <option value="{{ $val }}" {{ $value == $val ? 'selected' : '' }}>{{ $text }}</option>
            @endforeach
        </select>
    @elseif($type === 'textarea')
        <textarea 
            name="{{ $name }}" 
            id="{{ $id }}"
            rows="{{ $rows }}"
            placeholder="{{ $placeholder }}"
            {{ $required ? 'required' : '' }}
            {{ $attributes->merge(['class' => 'form-input']) }}
        >{{ old($name, $value) }}</textarea>
    @else
        <input 
            type="{{ $type }}" 
            name="{{ $name }}" 
            id="{{ $id }}"
            value="{{ old($name, $value) }}"
            placeholder="{{ $placeholder }}"
            {{ $required ? 'required' : '' }}
            {{ $attributes->merge(['class' => 'form-input']) }}
        />
    @endif

    <div id="{{ $name }}-error" class="form-error hidden"></div>
</div>
