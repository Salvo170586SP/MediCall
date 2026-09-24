@if ($paginator->hasPages())
<nav role="navigation" aria-label="Pagination Navigation" class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
    <div class="text-sm text-slate-600">
        <p class="text-slate-600">
              {{ __('Mostrando') }} <span class="font-semibold text-slate-900">{{ $paginator->firstItem() }}</span> - <span class="font-semibold text-slate-900">{{ $paginator->lastItem() }}</span>  {{ __('di') }} <span class="font-semibold text-slate-900">{{ $paginator->total() }}</span>
        </p>
    </div>

    <div class="flex flex-wrap items-center justify-center gap-2">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <span aria-disabled="true" aria-label="{{ __('pagination.previous') }}" class="inline-flex items-center gap-1 rounded-xl border border-slate-200 bg-slate-100 px-4 py-2 text-sm font-medium text-slate-400 cursor-not-allowed">
                {{-- icona --}}
                {{ __('Precedente') }}
            </span>
        @else
            <button wire:click="previousPage" wire:loading.attr="disabled" rel="prev" aria-label="{{ __('pagination.previous') }}" class="inline-flex items-center gap-1 rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-medium text-slate-700 transition hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-blue-300">
                {{-- icona --}}
                {{ __('Precedente') }}
            </button>
        @endif

        {{-- Pagination Elements --}}
        @foreach ($elements as $element)
            @if (is_string($element))
                <span class="inline-flex items-center rounded-xl border border-slate-200 bg-slate-100 px-4 py-2 text-sm font-medium text-slate-500">{{ $element }}</span>
            @endif

            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span aria-current="page" class="inline-flex items-center rounded-xl border border-blue-600 bg-blue-600 px-4 py-2 text-sm font-semibold text-white">{{ $page }}</span>
                    @else
                        <button wire:click="gotoPage({{ $page }})" wire:loading.attr="disabled" class="inline-flex items-center rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-medium text-slate-700 transition hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-blue-300">{{ $page }}</button>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <button wire:click="nextPage" wire:loading.attr="disabled" rel="next" aria-label="{{ __('pagination.next') }}" class="inline-flex items-center gap-1 rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-medium text-slate-700 transition hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-blue-300">
                {{ __('Successivo') }}
                {{-- icona --}}
            </button>
        @else
            <span aria-disabled="true" aria-label="{{ __('pagination.next') }}" class="inline-flex items-center gap-1 rounded-xl border border-slate-200 bg-slate-100 px-4 py-2 text-sm font-medium text-slate-400 cursor-not-allowed">
                {{ __('Successivo') }}
                {{-- icona --}}
            </span>
        @endif
    </div>
</nav>
@endif