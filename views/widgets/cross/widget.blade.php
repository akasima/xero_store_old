<div class="xe-shop ">
    <div class="container">
        <h2 class="xe-shop-wiget-title">{{$widgetConfig['title']}}</h2>
        <div class="cross-list">
            <div><a href="{{route('rich_shop.product', ['slug' => $product4->slug->slug])}}"><img src="{{ $product4Image->url() }}" alt=""></a></div>
            <div>
                <div><a href="{{route('rich_shop.product', ['slug' => $product1->slug->slug])}}"><img src="{{ $product1Image->url() }}" alt=""></a></div>
                <div><a href="{{route('rich_shop.product', ['slug' => $product3->slug->slug])}}"><img src="{{ $product3Image->url() }}" alt=""></a></div>
            </div>
            <div><a href="{{route('rich_shop.product', ['slug' => $product2->slug->slug])}}"><img src="{{ $product2Image->url() }}" alt=""></a></div>
        </div>
    </div>
</div>