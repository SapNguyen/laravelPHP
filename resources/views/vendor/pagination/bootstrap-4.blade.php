@if ($paginator->hasPages())
    <nav>
        <ul class="pagination" style="justify-content: right">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <li class="page-item disabled" aria-disabled="true" aria-label="@lang('pagination.previous')">
                    <span class="page-link" aria-hidden="true">&lsaquo;</span>
                </li>
            @else
                <li class="page-item">
                    <a class="page-link"  rel="prev" aria-label="@lang('pagination.previous')">&lsaquo;</a>
                </li>
            @endif

            <?php
        $start = $paginator->currentPage() - 2; // show 3 pagination links before current
        $end = $paginator->currentPage() + 2; // show 3 pagination links after current
        if($start < 1) {
            $start = 1; // reset start to 1
            $end += 1;
        } 
        if($end >= $paginator->lastPage() ) $end = $paginator->lastPage(); // reset end to last page
    ?>

    @if($start > 1)
        <li class="page-item">
            <a class="page-link" >{{1}}</a>
        </li>
        @if($paginator->currentPage() != 4)
            {{-- "Three Dots" Separator --}}
            <li class="page-item disabled" aria-disabled="true"><span class="page-link">...</span></li>
        @endif
    @endif
        @for ($i = $start; $i <= $end; $i++)
            <li class="page-item {{ ($paginator->currentPage() == $i) ? ' active' : '' }}">
                <a class="page-link" >{{$i}}</a>
            </li>
        @endfor
    @if($end < $paginator->lastPage())
        @if($paginator->currentPage() + 3 != $paginator->lastPage())
            {{-- "Three Dots" Separator --}}
            <li class="page-item disabled" aria-disabled="true"><span class="page-link">...</span></li>
        @endif
        <li class="page-item">
            <a class="page-link">{{$paginator->lastPage()}}</a>
        </li>
    @endif
            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <li class="page-item">
                    <a class="page-link" rel="next" aria-label="@lang('pagination.next')">&rsaquo;</a>
                </li>
            @else
                <li class="page-item disabled" aria-disabled="true" aria-label="@lang('pagination.next')">
                    <span class="page-link" aria-hidden="true">&rsaquo;</span>
                </li>
            @endif
        </ul>
    </nav>
@endif
