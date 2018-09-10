{{XeFrontend::js('/plugins/rich_shop/assets/js/widgets/spotSlider.js')->load()}}

<div class="xe-shop slider">
    <div class="container">
        <h2 class="xe-sr-only">비주얼 영역</h2>
        <div class="xe-spot-slider-view">
            <ul>
                @foreach ($items as $index => $item)
                    <li><a href="{{route('rich_shop.product', ['slug' => $item['product']->slug->slug])}}"><img src="{{$item['product_image']->url()}}" alt=""></a></li>
                @endforeach
            </ul>
        </div>
        <div class="xe-spot-slider-pager">
            <button class="xe-btn-prev"><i class="xi-angle-left-thin"></i><span class="xe-sr-only">이전 보기</span></button>
            <button class="xe-btn-next"><i class="xi-angle-right-thin"></i><span class="xe-sr-only">다음 보기</span></button>
        </div>
        <ul class="xe-spot-slider-direction">
            @foreach ($items as $index => $item)
                <li class="@if($index == 0) active @endif"><button></button></li>
            @endforeach
        </ul>
    </div>
</div>