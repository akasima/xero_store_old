{{XeFrontend::css('/assets/vendor/bootstrap/css/bootstrap.css')->load()}}
{{XeFrontend::js('/assets/vendor/bootstrap/js/bootstrap.min.js')->load()}}

<div class="xe-shop">
    <h2 class="xe-shop-wiget-title">{{$widgetConfig['title']}}</h2>
    <div class="tab-list">
        <!-- 탭이 하나 이상일 때만 표시 -->
        @if (count($tabItems) > 1)
        <ul class="tab-list-title nav nav-tabs">
            @foreach ($tabItems as $index => $tabItem)
                <li class=" @if($index == 0) active @endif "><a data-toglle="tab" href="#tab{{$index}}">{{$tabItem['tab_name']}}</a></li>
            @endforeach
        </ul>
        @endif
        <div class="tab-list-container tab-content">
            @foreach ($tabItems as $index => $tabItem)
                <div class="container tab-pane fade @if($index == 0) in active @endif " id="tab{{$index}}">
                    <!-- [D] 노드를 교체 할 경우 / display 할 경우 두 상황에 따라 추가 개발 필요 -->
                    <ul>
                        @for ($i=0; $i<3; $i++)
                            <li>
                                <a href="{{route('rich_shop.product', ['slug' => $tabItem['product'.$i]->slug->slug])}}">
                                    <div class="tab-list-img">
                                        <div class="tab-list-number">{{$i + 1}}</div>
                                        <img src="{{$tabItem['product'.$i.'_image']->url()}}" alt="">
                                    </div>
                                    <div class="tab-list-caption">
                                        <h3 class="default-list-text-title"><span class="xe-shop-tag black">new</span><span class="xe-shop-tag">best</span> {{$tabItem['product'.$i]->productName}}</h3>
                                        <p class="default-list-text-price">
                                            <span class="xe-sr-only">할인 전</span>
                                            <span class="through">{{number_format($tabItem['product'.$i]->price)}}원</span>
                                            <i class="xi-arrow-right"></i>
                                            <span class="xe-sr-only">할인 후</span>
                                            <span>{{number_format($tabItem['product'.$i]->price)}}원</span>
                                        </p>
                                    </div>
                                </a>
                            </li>
                        @endfor
                    </ul>
                </div>
            @endforeach
        </div>
    </div>
</div>