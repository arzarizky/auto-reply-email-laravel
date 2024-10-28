@if ($paginator->hasPages())
    <nav aria-label="Page navigation example" style="margin-top: 25px;">
        <ul class="pagination">

            <div class="pr-2" style="align-self: center;">
                Halaman
            </div>

            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <li class="page-item disabled">
                    <a class="page-link" href="#">
                        <i class='bx bx-chevron-left'></i>
                    </a>
                </li>
            @else
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->previousPageUrl() }}">
                        <i class='bx bx-chevron-left'></i>
                    </a>
                </li>
            @endif

            @foreach (range(1, $paginator->lastPage()) as $i)
                @if ($i >= $paginator->currentPage() && $i <= $paginator->currentPage())
                    @if ($i == $paginator->currentPage())
                        <li class="active page-item pe-1">
                            <span class="page-link">{{ $i }}</span>
                        </li>

                        <div class="ps-2 pe-2" style="align-self: center;">
                            dari {{ $paginator->lastPage() }}
                        </div>

                    @else
                        <li class="page-item">
                            <a class="page-link" href="{{ $paginator->url($i) }}">{{ $i }}</a>
                        </li>
                    @endif
                @endif
            @endforeach

            @if ($paginator->hasMorePages())
                <li class="page-item ps-2">
                    <a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next">
                       <i class='bx bx-chevron-right'></i>
                    </a>
                </li>
            @else
                <li class="page-item disabled">
                    <a class="page-link" ref="#" rel="next">
                       <i class='bx bx-chevron-right'></i>
                    </a>
                </li>
            @endif
        </ul>
    </nav>
@endif
