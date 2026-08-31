@props([
    'type' => 'button',
    'variant' => 'primary',
    'size' => 'md',
    'disabled' => false
])

@php
    $baseClass = 'btn';
    
    $variantClass = match($variant) {
        'primary' => 'btn-primary',
        'secondary' => 'btn-secondary',
        'danger' => 'btn-danger',
        default => 'btn-primary',
    };
    
    $sizeClass = match($size) {
        'sm' => 'btn-sm',
        'lg' => 'btn-lg',
        default => '',
    };
    
    $classes = "{$baseClass} {$variantClass} {$sizeClass}";
@endphp

<button 
    type="{{ $type }}" 
    {{ $disabled ? 'disabled' : '' }}
    {{ $attributes->merge(['class' => $classes]) }}
>
    {{ $slot }}
</button>
