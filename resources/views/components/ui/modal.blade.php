@props(['id', 'title' => '', 'maxWidth' => 'md'])

@php
    $maxWidthClass = match($maxWidth) {
        'sm' => 'sm:max-w-sm',
        'md' => 'sm:max-w-md',
        'lg' => 'sm:max-w-lg',
        'xl' => 'sm:max-w-xl',
        '2xl' => 'sm:max-w-2xl',
        default => 'sm:max-w-md',
    };
@endphp

<div id="{{ $id }}" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <!-- Background backdrop -->
    <div class="fixed inset-0 bg-gray-500/75 transition-opacity backdrop-blur-sm" aria-hidden="true" data-modal-close="{{ $id }}"></div>

    <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
        <!-- Modal panel -->
        <div class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 w-full {{ $maxWidthClass }}">
            
            <!-- Header -->
            <div class="bg-white px-4 py-3 border-b border-gray-100 sm:px-6 flex justify-between items-center">
                <h3 class="text-lg font-semibold leading-6 text-gray-900" id="modal-title">
                    {{ $title }}
                </h3>
                <button type="button" class="text-gray-400 hover:text-gray-500" data-modal-close="{{ $id }}">
                    <span class="sr-only">Close</span>
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Body -->
            <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                {{ $slot }}
            </div>

            <!-- Footer (Optional) -->
            @if(isset($footer))
                <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6 border-t border-gray-100">
                    {{ $footer }}
                </div>
            @endif
        </div>
    </div>
</div>
