<section class="xe-shop list">
    <div class="container">
        <h2 class="xe-shop-wiget-title">{{$widgetConfig['title']}}</h2>
        <ul class="default-list">
            @foreach ($items as $index => $item)
                <li>
                    <div class="default-list-img">
                        <a href="{{route('rich_shop.product', ['slug' => $item['product']->slug->slug])}}"><img src="{{$item['product']->getThumbnail('450x600')}}" alt="{{$item['product']->productName}}"></a>
                        <h4 class="xe-sr-only">sns 공유</h4>
                        <ul class="default-list-sns">
                            <li><a href="#"><i class="xi-heart-o"></i></a></li>
                            <li><a href="#"><i class="xi-facebook"></i></a></li>
                            <li><a href="#"><i class="xi-instagram"></i></a></li>
                        </ul>
                    </div>
                    <div class="default-list-text">
                        <a href="{{route('rich_shop.product', ['slug' => $item['product']->slug->slug])}}">
                            <h3 class="default-list-text-title">
                                <span class="xe-shop-tag black">new</span>
                                <span class="xe-shop-tag">best</span>
                                {{$item['product']->productName}}
                            </h3>
                            <p class="default-list-text-price">
                                <span class="xe-sr-only">할인 전</span>
                                <span class="through">{{number_format($item['product']->price)}}원</span>
                                <i class="xi-arrow-right"></i>
                                <span class="xe-sr-only">할인 후</span>
                                <span>{{number_format($item['product']->price)}}원</span>
                            </p>
                        </a>
                    </div>
                </li>
            @endforeach
        </ul>
    </div>
</section>