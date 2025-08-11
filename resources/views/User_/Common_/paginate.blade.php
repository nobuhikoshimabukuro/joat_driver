<style>
@media (max-width: 767.98px) { /* XS, SMサイズ（携帯サイズ） */
    .pagination li.page-item a.page-link,
    .pagination li.page-item span.page-link {
        padding: 0.5rem 0.65rem; /* ボタンの内側の余白を小さく */
        font-size: 0.9em; /* フォントサイズも小さく */
    }
}
.text-padination{
    color:#14507b;
}

</style>


@if(count($pagination_data) > 0)

    <div class="d-flex flex-wrap align-items-center justify-content-start">
        @if ($pagination_data->hasPages())
        <nav>
            <ul class="pagination justify-content-start flex-wrap m-0 mb-md-2">
                {{-- 前へリンク --}}

                @if (!$pagination_data->onFirstPage())
                    <li class="page-item">
                        <a class="page-link" href="{{ $pagination_data->appends(request()->query())->url(1) }}">最初</a>
                    </li>
                @endif


                @if ($pagination_data->onFirstPage())
                    <li class="page-item disabled"><span class="page-link">前へ</span></li>
                @else
                    <li class="page-item">
                        <a class="page-link" href="{{ $pagination_data->appends(request()->query())->previousPageUrl() }}" rel="prev">前へ</a>
                    </li>
                @endif

                {{-- ページ番号 --}}
                @foreach ($pagination_data->links()->elements as $element)
                    @if (is_string($element))
                        <li class="page-item disabled"><span class="page-link">{{ $element }}</span></li>
                    @endif
                
                    @if (is_array($element))                           
                        @foreach ($element as $page => $url)
                            @if ($page == $pagination_data->currentPage())
                                <li class="page-item active"><span class="page-link">{{ $page }}</span></li>
                            @else
                                <li class="page-item">
                                    <a class="page-link" href="{{ $pagination_data->appends(request()->query())->url($page) }}">{{ $page }}</a>
                                </li>
                            @endif
                        @endforeach
                    @endif
                @endforeach

                {{-- 次へリンク --}}
                @if ($pagination_data->hasMorePages())
                    <li class="page-item">
                        <a class="page-link" href="{{ $pagination_data->appends(request()->query())->nextPageUrl() }}" rel="next">次へ</a>
                    </li>
                @else
                    <li class="page-item disabled"><span class="page-link">次へ</span></li>
                @endif

                {{-- 最後へ --}}
                @if ($pagination_data->currentPage() < $pagination_data->lastPage())
                    <li class="page-item">
                        <a class="page-link" href="{{ $pagination_data->appends(request()->query())->url($pagination_data->lastPage()) }}">最後</a>
                    </li>
                @endif
            </ul>
        </nav>
        @endif

        <p class="m-0 m-md-1 small text-padination fw-bold ms-lg-auto text-start text-lg-end">
            全 {{ $pagination_data->total() }} 件中
            {{ $pagination_data->firstItem() }} 〜 {{ $pagination_data->lastItem() }} 件を表示
        </p>
    </div>

@else

<p class="m-0 m-md-1 small text-padination fw-bold">データなし</p>

@endif

