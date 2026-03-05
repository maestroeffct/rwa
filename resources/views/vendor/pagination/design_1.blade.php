@if ($paginator->hasPages())
    <div class="position-relative d-flex flex-column flex-lg-row align-items-center justify-content-lg-center w-100 p-16 border-top-gray-200 mt-16">

        <div class="design1-pagination__display-stat  text-gray-400 font-14 pl-lg-16">
            {{ trans('update.pagination_display_stat',[
                'start' => $paginator->firstItem(),
                'last' => $paginator->lastItem(),
                'total' => $paginator->total()
                ]) }}
        </div>

        <ul class="design1-pagination mt-12 mt-lg-0">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <li class="page-item disabled" aria-disabled="true" aria-label="@lang('pagination.previous')">
                    <span class="page-link" aria-hidden="true">
                        <x-iconsax-lin-arrow-left-1 class="icons" width="14px" height="14px"/>
                    </span>
                </li>
            @else
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="@lang('pagination.previous')">
                        <x-iconsax-lin-arrow-left-1 class="icons" width="14px" height="14px"/>
                    </a>
                </li>
            @endif

            {{-- Pagination Elements --}}
            @php
                $lastShownPage = 0;
            @endphp

            @foreach ($elements as $element)
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if (
                            $page <= 2 ||
                            $page >= $paginator->lastPage() - 1 ||
                            ($page >= $paginator->currentPage() - 2 && $page <= $paginator->currentPage() + 2)
                        )
                            {{-- "Three Dots" Separator --}}
                            @if ($page - $lastShownPage > 1)
                                <li class="page-item disabled" aria-disabled="true"><span class="page-link">...</span></li>
                            @endif

                            @if ($page == $paginator->currentPage())
                                <li class="page-item active" aria-current="page"><span class="page-link">{{ $page }}</span></li>
                            @else
                                <li class="page-item"><a class="page-link" href="{{ $url }}">{{ $page }}</a></li>
                            @endif

                            @php
                                $lastShownPage = $page;
                            @endphp
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="@lang('pagination.next')">
                        <x-iconsax-lin-arrow-right-1 class="icons" width="14px" height="14px"/>
                    </a>
                </li>
            @else
                <li class="page-item disabled" aria-disabled="true" aria-label="@lang('pagination.next')">
                    <span class="page-link" aria-hidden="true">
                        <x-iconsax-lin-arrow-right-1 class="icons" width="14px" height="14px"/>
                    </span>
                </li>
            @endif
        </ul>
    </div>
@endif
