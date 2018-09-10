<!-- 위젯 박스 -->
{{ uio('widgetbox', ['id' => $widgetBox->id, 'link'=>'편집하기']) }}
<!-- END 위젯 박스 -->

<section class="xe-shop list">
    <div class="container">
        <h2 class="xe-shop-wiget-title">NEW</h2>
        <div class="default-list-tab">
            <ul>
                @foreach ($categoryChildren as $item)
                    <li><a href="{{instanceRoute('index', ['categoryItemId' => $item->id, 'categoryItemDepth' => $config->get('categoryItemDepth') + 1])}}">{{xe_trans($item->word)}}</a></li>
                @endforeach
            </ul>
            <div class="xe-dropdown open">
                <button class="xe-btn" type="button" data-toggle="xe-dropdown">Low Price</button>
                <ul class="xe-dropdown-menu">
                    <li><a href="#">신상품</a></li>
                    <li><a href="#">인기상품</a></li>
                    <li><a href="#">낮은가격</a></li>
                    <li><a href="#">높은가격</a></li>
                </ul>
            </div>
        </div>

        <ul class="default-list">
            @foreach($paginate as $item)
                <li>
                    <div class="default-list-img">
                        <a href="{{instanceRoute('product', ['slug' => $item->slug->slug])}}"><img src="{{ $item->getThumbnail('450x600') }}" alt="{{ $item->productName }}"></a>
                        <h4 class="xe-sr-only">sns 공유</h4>
                        <ul class="default-list-sns">
                            <li><a href="#"><i class="xi-heart-o"></i></a></li>
                            <li><a href="#"><i class="xi-facebook"></i></a></li>
                            <li><a href="#"><i class="xi-instagram"></i></a></li>
                        </ul>
                    </div>
                    <div class="default-list-text">
                        <a href="{{instanceRoute('product', ['slug' => $item->slug->slug])}}">
                            <h3 class="default-list-text-title">
                                {{--<span class="xe-shop-tag black">new</span>--}}
                                {{--<span class="xe-shop-tag">best</span>--}}
                                {!! $item->productName !!}
                            </h3>
                            <p class="default-list-text-price">
                                <span class="xe-sr-only">할인 전</span>
                                <span class="through">{{number_format($item->price)}}원</span>
                                <i class="xi-arrow-right"></i>
                                <span class="xe-sr-only">할인 후</span>
                                <span>{{number_format($item->price)}}원</span>
                            </p>
                        </a>
                    </div>
                </li>
            @endforeach
        </ul>

        <div class="xe-pager">
            <h4 class="xe-sr-only">페이지 네비게이션</h4>
            {!! $paginate->render() !!}
        </div>
    </div>
</section>