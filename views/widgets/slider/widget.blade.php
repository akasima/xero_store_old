{{XeFrontend::js('/plugins/rich_shop/assets/js/widgets/slider.js')->load()}}

<div class="xe-shop">
    <div class="container">
        <h2 class="xe-shop-wiget-title">{{$widgetConfig['title']}}</h2>
        <div class="xe-shop-slider">
            <div class="xe-shop-slider-view">
                <ul>
                    <!-- [D] 가운대 위치할 경우 active 클래스 추가 부탁드립니다.  -->
                    @foreach ($items as $index => $item)
                    <li class="image-list @if($index == 0) active @endif "><a href="#" data-url="{{route('rich_shop.product', ['slug' => $item['product']->slug->slug])}}">
                            <img src="{{$item['product_image']->url()}}" alt="">
                            <!-- [D] 엔터는 1회 한하여 사용할수 있도록 한다. 한줄에 들어가는 한글 최대 갯수 / 영문  -->
                            <div class="xe-shop-slider-caption">
                                <p class="xe-shop-slider-title">{!! $item['product_title'] !!}</p>
                                <p class="xe-shop-slider-comment">{!! $item['product_comment'] !!}</p>
                            </div>
                        </a></li>
                    @endforeach
                </ul>
            </div>
            <button type="button" class="xe-btn xe-btn-slider-prev btn-prev"><i class="xi-angle-left-thin"></i></button>
            <button type="button" class="xe-btn xe-btn-slider-next btn-next"><i class="xi-angle-right-thin"></i></button>
        </div>
    </div>
</div>