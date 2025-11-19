@if ($paginator->hasPages() || request('show') === 'all')
<div class="mt-3 p-2 border rounded bg-white dark:bg-neutral-900 dark:border-neutral-500">

    <div class="flex items-center justify-between text-sm">

        {{-- 🔹 Left: Record Count + Show All --}}
        <div class="flex items-center gap-3 text-gray-600 dark:text-gray-400">

            {{-- Count --}}
            <span>
                @if(request('show') === 'all')
                    Showing <strong>{{ $paginator->total() }}</strong> records
                @else
                    Showing <strong>{{ $paginator->firstItem() }}</strong>–<strong>{{ $paginator->lastItem() }}</strong>
                    of <strong>{{ $paginator->total() }}</strong>
                @endif
            </span>

            {{-- Show All --}}
            @if($paginator->total() > $paginator->perPage())
            <a href="{{ request('show') === 'all'
                        ? request()->fullUrlWithoutQuery('show')
                        : request()->fullUrlWithQuery(['show' => 'all']) }}"
               class="px-2 py-1 bg-blue-600 text-white rounded text-xs hover:bg-blue-700 transition">
                {{ request('show') === 'all' ? 'Paginate' : 'Show All' }}
            </a>
            @endif

        </div>

        {{-- 🔹 Right: Pagination --}}
        @if ($paginator->hasPages() && request('show') !== 'all')
        <nav class="flex items-center space-x-1">

            {{-- Prev --}}
            @if ($paginator->onFirstPage())
                <span class="px-2 py-1 text-gray-400">‹</span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}"
                   class="px-2 py-1 rounded bg-blue-50 dark:bg-blue-900/40 text-blue-600 dark:text-blue-300 hover:bg-blue-100 dark:hover:bg-blue-800 text-xs">
                    ‹
                </a>
            @endif

            {{-- First 3 and last page --}}
            @foreach ($elements as $element)
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page <= 3 || $page == $paginator->lastPage())
                            @if ($page == $paginator->currentPage())
                                <span class="px-2 py-1 bg-blue-600 text-white rounded text-xs">{{ $page }}</span>
                            @else
                                <a href="{{ $url }}"
                                   class="px-2 py-1 rounded bg-blue-50 dark:bg-blue-900/40 text-blue-600 dark:text-blue-300 hover:bg-blue-100 dark:hover:bg-blue-800 text-xs">
                                    {{ $page }}
                                </a>
                            @endif
                        @elseif ($page == 4)
                            <span class="px-2 py-1 text-gray-500">…</span>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next --}}
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}"
                   class="px-2 py-1 rounded bg-blue-50 dark:bg-blue-900/40 text-blue-600 dark:text-blue-300 hover:bg-blue-100 dark:hover:bg-blue-800 text-xs">
                    ›
                </a>
            @else
                <span class="px-2 py-1 text-gray-400">›</span>
            @endif

        </nav>
        @endif

    </div>

</div>
@endif
