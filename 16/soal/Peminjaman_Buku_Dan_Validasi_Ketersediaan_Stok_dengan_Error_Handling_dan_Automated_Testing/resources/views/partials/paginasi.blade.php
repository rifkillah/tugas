@if ($paginator->hasPages())
    <nav class="pagination" aria-label="Navigasi halaman">
        <div class="pagination__info">
            Menampilkan {{ $paginator->firstItem() }}&ndash;{{ $paginator->lastItem() }}
            dari {{ $paginator->total() }} data
        </div>

        <div class="pagination__links">
            @if ($paginator->onFirstPage())
                <span class="pagination__link is-disabled" aria-disabled="true">&lsaquo;</span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" class="pagination__link" rel="prev" aria-label="Halaman sebelumnya">&lsaquo;</a>
            @endif

            @foreach ($paginator->getUrlRange(max(1, $paginator->currentPage() - 2), min($paginator->lastPage(), $paginator->currentPage() + 2)) as $halaman => $url)
                @if ($halaman === $paginator->currentPage())
                    <span class="pagination__link is-active" aria-current="page">{{ $halaman }}</span>
                @else
                    <a href="{{ $url }}" class="pagination__link">{{ $halaman }}</a>
                @endif
            @endforeach

            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" class="pagination__link" rel="next" aria-label="Halaman berikutnya">&rsaquo;</a>
            @else
                <span class="pagination__link is-disabled" aria-disabled="true">&rsaquo;</span>
            @endif
        </div>
    </nav>
@endif
