@if ($paginator->hasPages())
<nav role="navigation" aria-label="{{ __('Pagination Navigation') }}"
     class="flex items-center justify-between">

    {{-- Mobile --}}
    <div class="flex justify-between flex-1 sm:hidden gap-2">
        @if ($paginator->onFirstPage())
            <span class="px-3 py-1.5 text-xs font-medium rounded-md border
                bg-gray-200 text-gray-400 dark:bg-neutral-700 dark:border-neutral-600">
                {!! __('pagination.previous') !!}
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}"
               class="px-3 py-1.5 text-xs font-medium rounded-md border
               bg-[color:var(--flux-primary)]
               text-[color:var(--flux-primary-foreground)]
               border-[color:var(--flux-primary)]">
                {!! __('pagination.previous') !!}
            </a>
        @endif

        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}"
               class="px-3 py-1.5 text-xs font-medium rounded-md border
               bg-[color:var(--flux-primary)]
               text-[color:var(--flux-primary-foreground)]
               border-[color:var(--flux-primary)]">
                {!! __('pagination.next') !!}
            </a>
        @else
            <span class="px-3 py-1.5 text-xs font-medium rounded-md border
                bg-gray-200 text-gray-400 dark:bg-neutral-700 dark:border-neutral-600">
                {!! __('pagination.next') !!}
            </span>
        @endif
    </div>

    {{-- Desktop --}}
    <div class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between">

        <p class="text-xs text-gray-600 dark:text-gray-400">
            Showing
            <span class="font-medium">{{ $paginator->firstItem() }}</span>
            –
            <span class="font-medium">{{ $paginator->lastItem() }}</span>
            of
            <span class="font-medium">{{ $paginator->total() }}</span>
        </p>

        <span class="inline-flex items-center gap-1">

            {{-- Previous --}}
            @if ($paginator->onFirstPage())
                <span class="w-8 h-8 flex items-center justify-center rounded-md border
                    bg-gray-200 text-gray-400 dark:bg-neutral-700 dark:border-neutral-600">
                    ‹
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}"
                   class="w-8 h-8 flex items-center justify-center rounded-md border
                   bg-[color:var(--flux-primary)]
                   text-[color:var(--flux-primary-foreground)]
                   border-[color:var(--flux-primary)]">
                    ‹
                </a>
            @endif

            {{-- Pages --}}
            @foreach ($elements as $element)

                {{-- Dots --}}
                @if (is_string($element))
                    <span class="w-8 h-8 flex items-center justify-center text-xs text-gray-400">
                        {{ $element }}
                    </span>
                @endif

                {{-- Page links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)

                        {{-- Active page (UPDATED: Black/White Button Style) --}}
                        @if ($page == $paginator->currentPage())
                            <span class="w-8 h-8 flex items-center justify-center text-xs font-bold
                                rounded-md border
                                bg-gray-800 text-white border-black
                                dark:bg-white dark:text-black dark:border-white">
                                {{ $page }}
                            </span>
                        @else
                            <a href="{{ $url }}"
                               class="w-8 h-8 flex items-center justify-center text-xs
                               rounded-md border
                               bg-[color:var(--flux-primary)]
                               text-[color:var(--flux-primary-foreground)]
                               border-[color:var(--flux-primary)]
                               opacity-80 hover:opacity-100">
                                {{ $page }}
                            </a>
                        @endif

                    @endforeach
                @endif

            @endforeach

            {{-- Next --}}
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}"
                   class="w-8 h-8 flex items-center justify-center rounded-md border
                   bg-[color:var(--flux-primary)]
                   text-[color:var(--flux-primary-foreground)]
                   border-[color:var(--flux-primary)]">
                    ›
                </a>
            @else
                <span class="w-8 h-8 flex items-center justify-center rounded-md border
                    bg-gray-200 text-gray-400 dark:bg-neutral-700 dark:border-neutral-600">
                    ›
                </span>
            @endif

        </span>
    </div>
</nav>
@endif