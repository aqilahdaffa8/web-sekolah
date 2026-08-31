@props(['type' => 'text', 'lines' => 1])

@if($type === 'card')
    <div {{ $attributes->merge(['class' => 'card p-4 space-y-4']) }}>
        <div class="skeleton h-32 w-full rounded-md"></div>
        <div class="skeleton h-4 w-3/4"></div>
        <div class="skeleton h-4 w-1/2"></div>
    </div>
@elseif($type === 'table')
    <div {{ $attributes->merge(['class' => 'space-y-3']) }}>
        @for($i = 0; $i < $lines; $i++)
            <div class="flex space-x-4">
                <div class="skeleton h-10 w-full rounded"></div>
            </div>
        @endfor
    </div>
@else
    <div {{ $attributes->merge(['class' => 'space-y-3']) }}>
        @for($i = 0; $i < $lines; $i++)
            <div class="skeleton h-4 w-full rounded"></div>
        @endfor
    </div>
@endif
