<!-- 신상품 위젯 -->
<div class="xe-shop">
    <h2 class="xe-shop-wiget-title">BEST</h2>
    <div class="tab-list">
        <div class="tab-list-container">
            <div class="container">
                <!-- [D] 노드를 교체 할 경우 / display 할 경우 두 상황에 따라 추가 개발 필요 -->
                <ul>
                    <li>
                        <a href="#">
                            <div class="tab-list-img">
                                <div class="tab-list-number">1</div>
                                <img src="/plugins/rich_shop/assets/img/tmp_tablist.jpg" alt="">
                            </div>
                            <div class="tab-list-caption">
                                <h3 class="default-list-text-title"><span class="xe-shop-tag black">new</span><span class="xe-shop-tag">best</span> 상품명 PRODUCT 상품명 PRODUCT 상품명 상품명 상품명 상품명 상품명 상품명 상품명 상품명</h3>
                                <p class="default-list-text-price">
                                    <span class="xe-sr-only">할인 전</span>
                                    <span class="through">150,000원</span>
                                    <span class="xe-sr-only">할인 후</span>
                                    <span>100,000원</span>
                                </p>
                            </div>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
<!-- END 신상품 위젯 -->

<section class="xe-shop list">
    <div class="container">
        <h2 class="xe-shop-wiget-title">NEW</h2>
        <div class="default-list-tab">
            <ul>
                @foreach ($categoryChildren as $item)
                    <li><a href="{{route('rich_shop.items', ['categoryItemId' => $item->id, 'categoryItemDepth' => $config->get('categoryItemDepth') + 1])}}">{{xe_trans($item->word)}}</a></li>
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
                        <a href="{{route('rich_shop.product', ['slug' => $item->slug->slug])}}"><img src="{{ $item->getThumbnail('450x600') }}" alt="{{ $item->productName }}"></a>
                        <h4 class="xe-sr-only">sns 공유</h4>
                        <ul class="default-list-sns">
                            <li><a href="#"><i class="xi-heart-o"></i></a></li>
                            <li><a href="#"><i class="xi-facebook"></i></a></li>
                            <li><a href="#"><i class="xi-instagram"></i></a></li>
                        </ul>
                    </div>
                    <div class="default-list-text">
                        <a href="{{route('rich_shop.product', ['slug' => $item->slug->slug])}}">
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
            {{--<ul>--}}
            {{--<li><a href="#"><i class="xi-angle-left-thin"></i></a></li>--}}
            {{--<li><a href="#">1</a><span class="xe-sr-only">활성화됨</span></li>--}}
            {{--<li><a href="#">2</a><span class="xe-sr-only">활성화됨</span></li>--}}
            {{--<li class="active"><a href="#">3</a><span class="xe-sr-only">활성화됨</span></li>--}}
            {{--<li><a href="#">4</a><span class="xe-sr-only">활성화됨</span></li>--}}
            {{--<li><a href="#">5</a><span class="xe-sr-only">활성화됨</span></li>--}}
            {{--<li><a href="#">6</a><span class="xe-sr-only">활성화됨</span></li>--}}
            {{--<li><a href="#">7</a><span class="xe-sr-only">활성화됨</span></li>--}}
            {{--<li><a href="#">8</a><span class="xe-sr-only">활성화됨</span></li>--}}
            {{--<li><a href="#">99999</a><span class="xe-sr-only">활성화됨</span></li>--}}
            {{--<li><a href="#"><i class="xi-angle-right-thin"></i></a></li>--}}
            {{--</ul>--}}
        </div>
    </div>
</section>