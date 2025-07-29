@if ($paginator->hasPages())
    <nav class="flex flex-col sm:flex-row items-center justify-between bg-white rounded-lg p-4 shadow-sm border">
        <!-- Info Section -->
        <div class="flex items-center gap-3 mb-4 sm:mb-0">
            <div class="text-sm text-gray-600">
                Menampilkan
                <span class="font-medium text-gray-900">{{ $paginator->firstItem() ?? 0 }}</span>
                -
                <span class="font-medium text-gray-900">{{ $paginator->lastItem() ?? 0 }}</span>
                dari
                <span class="font-medium text-gray-900">{{ $paginator->total() }}</span>
                data
            </div>
        </div>

        <!-- Pagination Links -->
        <div class="flex items-center gap-1">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <span class="flex items-center justify-center w-8 h-8 bg-gray-100 text-gray-400 rounded cursor-not-allowed">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" data-page="{{ $paginator->currentPage() - 1 }}"
                    class="pagination-link flex items-center justify-center w-8 h-8 bg-white text-gray-500 rounded border border-gray-300 hover:bg-gray-50 hover:text-gray-700 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </a>
            @endif

            {{-- Page Numbers --}}
            @php
                $start = max($paginator->currentPage() - 2, 1);
                $end = min($start + 4, $paginator->lastPage());
                $start = max($end - 4, 1);
            @endphp

            {{-- First page --}}
            @if ($start > 1)
                <a href="{{ $paginator->url(1) }}" data-page="1"
                    class="pagination-link flex items-center justify-center w-8 h-8 bg-white text-gray-700 rounded border border-gray-300 hover:bg-gray-50 hover:text-gray-900 transition-colors">
                    1
                </a>
                @if ($start > 2)
                    <span class="flex items-center justify-center w-8 h-8 text-gray-400">...</span>
                @endif
            @endif

            {{-- Page Range --}}
            @for ($page = $start; $page <= $end; $page++)
                @if ($page == $paginator->currentPage())
                    <span
                        class="flex items-center justify-center w-8 h-8 bg-blue-600 text-white font-medium rounded border border-blue-600">
                        {{ $page }}
                    </span>
                @else
                    <a href="{{ $paginator->url($page) }}" data-page="{{ $page }}"
                        class="pagination-link flex items-center justify-center w-8 h-8 bg-white text-gray-700 rounded border border-gray-300 hover:bg-gray-50 hover:text-gray-900 transition-colors">
                        {{ $page }}
                    </a>
                @endif
            @endfor

            {{-- Last page --}}
            @if ($end < $paginator->lastPage())
                @if ($end < $paginator->lastPage() - 1)
                    <span class="flex items-center justify-center w-8 h-8 text-gray-400">...</span>
                @endif
                <a href="{{ $paginator->url($paginator->lastPage()) }}" data-page="{{ $paginator->lastPage() }}"
                    class="pagination-link flex items-center justify-center w-8 h-8 bg-white text-gray-700 rounded border border-gray-300 hover:bg-gray-50 hover:text-gray-900 transition-colors">
                    {{ $paginator->lastPage() }}
                </a>
            @endif

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" data-page="{{ $paginator->currentPage() + 1 }}"
                    class="pagination-link flex items-center justify-center w-8 h-8 bg-white text-gray-500 rounded border border-gray-300 hover:bg-gray-50 hover:text-gray-700 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </a>
            @else
                <span class="flex items-center justify-center w-8 h-8 bg-gray-100 text-gray-400 rounded cursor-not-allowed">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                    </a>
            @endif
        </div>

        <!-- Items per page selector -->
        <div class="flex items-center gap-2 mt-4 sm:mt-0">
            <label class="text-sm text-gray-600">Per halaman:</label>
            <select
                class="per-page-selector px-2 py-1 bg-white border border-gray-300 rounded text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
                <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
            </select>
        </div>
    </nav>
@endif

<style>
    .pagination-link {
        text-decoration: none;
    }

    .pagination-link:hover {
        text-decoration: none;
    }

    .per-page-selector {
        min-width: 60px;
    }

    /* Responsive adjustments */
    @media (max-width: 640px) {

        .pagination-link,
        .pagination-link span {
            width: 32px;
            height: 32px;
            font-size: 14px;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Handle per-page selector change
        const perPageSelector = document.querySelector('.per-page-selector');
        if (perPageSelector) {
            perPageSelector.addEventListener('change', function () {
                const currentUrl = new URL(window.location.href);
                currentUrl.searchParams.set('per_page', this.value);
                currentUrl.searchParams.set('page', 1); // Reset to first page

                if (typeof fetchPresensi === 'function') {
                    // If using AJAX (like in the attendance page)
                    fetchPresensi(1);
                } else {
                    // If using regular page reload
                    window.location.href = currentUrl.toString();
                }
            });
        }
    });
</script>