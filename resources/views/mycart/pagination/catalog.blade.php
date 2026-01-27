@if ($paginator->hasPages())
<ul class="pagination pagination-lg pt-2 pt-md-3">

    {{-- Previous Page --}}
    <li class="page-item {{ $paginator->onFirstPage() ? 'disabled' : '' }} me-auto">
        @if ($paginator->onFirstPage())
            <span class="page-link d-flex align-items-center h-100 fs-lg px-2">
                <i class="ci-chevron-left mx-1"></i>
            </span>
        @else
            <a class="page-link d-flex align-items-center h-100 fs-lg px-2"
               href="{{ $paginator->previousPageUrl() }}"
               aria-label="Previous page">
                <i class="ci-chevron-left mx-1"></i>
            </a>
        @endif
    </li>

    {{-- Pagination Numbers --}}
    @foreach ($elements as $element)

        {{-- Dots --}}
        @if (is_string($element))
            <li class="page-item">
                <span class="page-link pe-none">{{ $element }}</span>
            </li>
        @endif

        {{-- Page Links --}}
        @if (is_array($element))
            @foreach ($element as $page => $url)
                @if ($page == $paginator->currentPage())
                    <li class="page-item active" aria-current="page">
                        <span class="page-link">
                            {{ $page }}
                            <span class="visually-hidden">(current)</span>
                        </span>
                    </li>
                @else
                    <li class="page-item">
                        <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                    </li>
                @endif
            @endforeach
        @endif

    @endforeach

    {{-- Next Page --}}
    <li class="page-item {{ $paginator->hasMorePages() ? '' : 'disabled' }} ms-auto">
        @if ($paginator->hasMorePages())
            <a class="page-link d-flex align-items-center h-100 fs-lg px-2"
               href="{{ $paginator->nextPageUrl() }}"
               aria-label="Next page">
                <i class="ci-chevron-right mx-1"></i>
            </a>
        @else
            <span class="page-link d-flex align-items-center h-100 fs-lg px-2">
                <i class="ci-chevron-right mx-1"></i>
            </span>
        @endif
    </li>

</ul>
@endif
