@props(['headers' => []])

<div class="overflow-x-auto bg-white rounded-lg border border-gray-200">
    <table class="data-table">
        <thead>
            <tr>
                @foreach($headers as $header)
                    <th>{{ $header }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody id="{{ $attributes->get('id', 'table-body') }}">
            {{ $slot }}
        </tbody>
    </table>
    
    <!-- Empty State -->
    <div id="{{ $attributes->get('id', 'table-body') }}-empty" class="hidden p-8 text-center text-gray-500">
        {{ $empty ?? 'Data tidak ditemukan.' }}
    </div>

    <!-- Loading State -->
    <div id="{{ $attributes->get('id', 'table-body') }}-loading" class="hidden p-8 text-center text-gray-500">
        <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-blue-500 inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        Memuat data...
    </div>
</div>

@if(isset($pagination))
    <div class="mt-4 flex items-center justify-between">
        {{ $pagination }}
    </div>
@endif
