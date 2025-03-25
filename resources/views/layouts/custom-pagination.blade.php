@if ($paginator->hasPages())
    <nav class="flex justify-between items-center">
        <ul class="inline-flex items-center">
            @if ($paginator->onFirstPage())
                <li class="disabled"><span class="px-3 py-2 text-gray-500">« Previous</span></li>
            @else
                <li><a href="{{ $paginator->previousPageUrl() }}" class="px-3 py-2 text-blue-600 hover:text-blue-800">« Previous</a></li>
            @endif

            {{-- Page Numbers --}}
            @foreach ($elements as $element)
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li><span class="px-3 py-2 text-blue-600 font-bold">{{ $page }}</span></li>
                        @else
                            <li><a href="{{ $url }}" class="px-3 py-2 text-blue-600 hover:text-blue-800">{{ $page }}</a></li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            @if ($paginator->hasMorePages())
                <li><a href="{{ $paginator->nextPageUrl() }}" class="px-3 py-2 text-blue-600 hover:text-blue-800">Next »</a></li>
            @else
                <li class="disabled"><span class="px-3 py-2 text-gray-500">Next »</span></li>
            @endif
        </ul>
    </nav>
@endif
