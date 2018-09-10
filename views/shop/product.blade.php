<div class="xe-row">
    <div class="xe-col-sm-6">
        <ul>
            <li>Home</li>
            @foreach($breadcrumbs as $item)
                <li>{{xe_trans($item->word)}}</li>
            @endforeach
        </ul>
    </div>
</div>
<div class="xe-row">
    <div class="xe-col-sm-6">
        {!! $product->getThumbnail(Akasima\RichShop\Skins\ShopSkin::Thumb1)->render() !!}

        <div class="image-map">
            Image Map
        </div>
    </div>

    <div class="xe-col-sm-6">
        <div class="row price">
            가격 들
            <div>{{$product->price}}</div>
        </div>

        <div class="row delivery">
            배송 방법
            <div>{{$product->price}}</div>
        </div>

        <div class="row options">
            상품 옵션
            <div>{{$product->price}}</div>
        </div>

        <div class="row options">
            구매 선택들
            <div>{{$product->price}}</div>

            <div>총 상품긍맥수량 {{$product->price}} 원</div>
        </div>

        <div class="row buttons">
            <button type="button">카트 담기</button>
            <button type="button">구매하기</button>
            <button type="button">콕</button>
        </div>
    </div>
</div>

<div class="xe-row">
    {!! compile(\Akasima\RichShop\Plugin::getId(), $product->description, true) !!}
</div>

<div class="xe-row">
    상품 판매자 정보
</div>

<div class="xe-row">
    상품 평
</div>

<div class="xe-row">
    이것 저것
</div>