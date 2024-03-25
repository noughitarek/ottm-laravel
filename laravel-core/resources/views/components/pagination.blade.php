
<div class="row">
    <div class="col-sm-12 col-md-5">
        <div class="dataTables_info" id="datatables-orders_info" role="status" aria-live="polite">
            Showing {{ $paginator->firstItem() }} to {{ $paginator->lastItem() }} of {{ $paginator->total() }} entries
        </div>
    </div>
    @if ($paginator->hasPages())
    <div class="col-sm-12 col-md-7">
        <div class="dataTables_paginate paging_simple_numbers" id="datatables-orders_paginate">
            <ul class="pagination" style="list-style: none;">
                {{-- Previous Page Link --}}
                @if ($paginator->onFirstPage())
                    <li class="page-item disabled mx-2" style="display: inline-block;" aria-disabled="true">
                        <span class="page-link">@lang('pagination.previous')</span>
                    </li>
                @else
                    <li class="page-item mx-2" style="display: inline-block;">
                        <a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev">@lang('pagination.previous')</a>
                    </li>
                @endif

                {{-- Pagination Elements --}}
                @foreach ($elements as $element)
                    {{-- "Three Dots" Separator --}}
                    @if (is_string($element))
                        <li class="page-item disabled mx-2" aria-disabled="true" style="display: inline-block;" ><span class="page-link">{{ $element }}</span></li>
                    @endif
                    {{-- Array Of Links --}}
                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            @if ($page == $paginator->currentPage())
                                <li class="page-item active mx-2" aria-current="page" style="display: inline-block;"><span class="page-link">{{ $page }}</span></li>
                            @else
                                <li class="page-item mx-2" style="display: inline-block;"><a class="page-link" href="{{ $url }}">{{ $page }}</a></li>
                            @endif
                        @endforeach
                    @endif
                @endforeach
                @if ($paginator->hasMorePages())
                    <li class="paginate_button page-item next mx-2" style="display: inline-block;" id="datatables-orders_next">
                        <a href="{{ $paginator->nextPageUrl() }}" aria-controls="datatables-orders" aria-role="link" data-dt-idx="next" tabindex="0" class="page-link">@lang('pagination.next')</a>
                    </li>
                @else
                    <li class="paginate_button page-item next disabled mx-2" style="display: inline-block;" id="datatables-orders_next">
                        @lang('pagination.next')
                    </li>
                @endif
            </ul>
        </div>
    </div>
</div>
@endif