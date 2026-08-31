@props(['status'])

@php
    $statusClass = match(strtolower($status)) {
        'pending' => 'badge-pending',
        'paid' => 'badge-paid',
        'completed' => 'badge-completed',
        'open' => 'badge-open',
        'closed' => 'badge-closed',
        'approved' => 'badge-approved',
        'rejected' => 'badge-rejected',
        'active' => 'badge-active',
        default => 'bg-gray-100 text-gray-800',
    };
@endphp

<span class="badge {{ $statusClass }} {{ $attributes->get('class') }}">
    {{ $slot->isEmpty() ? ucfirst($status) : $slot }}
</span>
