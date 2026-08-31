@props([
    'title',
    'value' => '0',
    'icon' => null,
    'color' => 'blue'
])

@php
    $colorClass = match($color) {
        'blue' => 'bg-blue-100 text-blue-600',
        'green' => 'bg-green-100 text-green-600',
        'red' => 'bg-red-100 text-red-600',
        'yellow' => 'bg-yellow-100 text-yellow-600',
        'purple' => 'bg-purple-100 text-purple-600',
        default => 'bg-gray-100 text-gray-600',
    };
@endphp

<div class="card flex items-center p-4">
    <div class="p-3 rounded-lg {{ $colorClass }} mr-4">
        @if($icon)
            {!! $icon !!}
        @else
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
        @endif
    </div>
    <div>
        <p class="text-sm font-medium text-gray-500 truncate">{{ $title }}</p>
        <p class="text-2xl font-bold text-gray-900" id="{{ $attributes->get('id') }}">{{ $value }}</p>
    </div>
</div>
