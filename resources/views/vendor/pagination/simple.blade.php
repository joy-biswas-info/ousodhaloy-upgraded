@if ($paginator->hasPages())
<nav class="flex items-center justify-between mt-4">
    <p class="text-xs text-gray-500">
        Showing {{ $paginator->firstItem() }} – {{ $paginator->lastItem() }} of {{ $paginator->total() }} results
    </p>
    <div class="flex items-center gap-1">
        {{-- Previous --}}
        @if ($paginator->onFirstPage())
            <span class="px-3 py-1.5 text-xs text-gray-300 bg-white border border-gray-200 rounded-lg cursor-not-allowed">← Prev</span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" class="px-3 py-1.5 text-xs text-gray-600 bg-white border border-gray-200 rounded-lg hover:bg-teal-50 hover:text-teal-700 hover:border-teal-300 transition-colors">← Prev</a>
        @endif

        {{-- Page numbers --}}
        @foreach ($elements as $element)
            @if (is_string($element))
                <span class="px-2 py-1.5 text-xs text-gray-400">{{ $element }}</span>
            @endif

            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span class="px-3 py-1.5 text-xs bg-teal-600 text-white rounded-lg font-bold">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}" class="px-3 py-1.5 text-xs text-gray-600 bg-white border border-gray-200 rounded-lg hover:bg-teal-50 hover:text-teal-700 hover:border-teal-300 transition-colors">{{ $page }}</a>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" class="px-3 py-1.5 text-xs text-gray-600 bg-white border border-gray-200 rounded-lg hover:bg-teal-50 hover:text-teal-700 hover:border-teal-300 transition-colors">Next →</a>
        @else
            <span class="px-3 py-1.5 text-xs text-gray-300 bg-white border border-gray-200 rounded-lg cursor-not-allowed">Next →</span>
        @endif
    </div>
</nav>
@endif
