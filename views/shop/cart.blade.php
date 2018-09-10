{{XeFrontend::js('/plugins/rich_shop/assets/js/shop.js')->load()}}

<div class="shop-container">
    <form method="get" action="{{route('rich_shop.order')}}">
        <div class="cart-list">
            <ul>
                @foreach ($carts as $cart)
                    <div class="row">
                        <li>
                            <div class="xe-col-sm-3">
                                {{ $cart->product->product_name }}
                            </div>
                            <div class="xe-col-sm-9">
                                @foreach ($cart->cartOptions as $cartOption)
                                    <meta name="csrf-token" content="{{ csrf_token() }}">
                                    <ul>
                                        <li class="option-box option-{{$cartOption->option_id}}" data-url-update-quantity="{{route('rich_shop.cart.quantity.update', ['id' => $cartOption->id])}}">
                                            <input type="checkbox" class="option-select" name="id[]" value="{{$cartOption->id}}" checked="checked" data-amount="{{($cart->product->price + $cartOption->option->additional_price) * $cartOption->quantity}}" data-delivery-fee="0"/>
                                            {{$cartOption->option->option_name}} /
                                            <span class="price">{{$cart->product->price + $cartOption->option->additional_price}}</span> 원 x

                                            <button type="button" class="decrease"><i class="xi-minus-thin"></i><span class="xe-sr-only">감소</span></button>
                                            <span class="quantity">{{$cartOption->quantity}}</span> 개
                                            <button type="button" class="increase"><i class="xi-plus-thin"></i><span class="xe-sr-only">증가</span></button>

                                            =

                                            <span class="amount">{{($cart->product->price + $cartOption->option->additinal_price) * $cartOption->quantity}}</span> 원
                                        </li>
                                    </ul>
                                @endforeach
                            </div>
                        </li>
                    </div>
                @endforeach
            </ul>
            <input type="checkbox" class="check-all check-relative" checked="checked" data-relative='[name="id[]"]'>
            <button type="button" class="xe-btn-danger deleteCartItem" data-url-delete-carts="{{ route('rich_shop.cart.destroy') }}">삭제</button>
        </div>

        <div class="summary">
            <div>
                <div>
                    상품가격 <span class="amount">{{number_format($summary['amount'])}}</span>
                    배송비 <span class="deliveryFee">{{ number_format($summary['deliveryFee']) }}</span>
                </div>
                <div>
                    <span class="sum">합계 {{number_format($summary['amount'] + $summary['deliveryFee'])}}</span>
                </div>
            </div>
        </div>
        <div class="order">
            <button type="submit" class="xe-btn-primary">주문하기</button>
        </div>
    </form>
</div>
