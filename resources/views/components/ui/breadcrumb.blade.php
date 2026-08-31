@props(['links' => []])

<nav class="flex text-sm text-gray-500 mb-4" aria-label="Breadcrumb">
    <ol class="inline-flex items-center space-x-1 md:space-x-3">
        @foreach($links as $name => $url)
            <li class="inline-flex items-center">
                @if(!$loop->first)
                    <svg class="w-3 h-3 text-gray-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                    </svg>
                @endif
                
                @if($url)
                    <a href="{{ $url }}" class="inline-flex items-center font-medium hover:text-blue-600">
                        {{ $name }}
                    </a>
                @else
                    <span class="font-medium text-gray-700">{{ $name }}</span>
                @endif
            </li>
        @endforeach
    </ol>
</nav>
