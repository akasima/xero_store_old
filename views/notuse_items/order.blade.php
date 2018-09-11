{{XeFrontend::js($theme::asset('js/shop.js'))->load()}}

<script src="//dmaps.daum.net/map_js_init/postcode.v2.js"></script>
<script>
    function foldDaumPostcode2(fieldId) {
        $('#'+fieldId+'-daumPostcodeWrap').hide();
    }

    function execDaumPostcode2(fieldId) {
        // 현재 scroll 위치를 저장해놓는다.
        var currentScroll = Math.max(document.body.scrollTop, document.documentElement.scrollTop);
        new daum.Postcode({
            oncomplete: function(data) {
                // 검색결과 항목을 클릭했을때 실행할 코드를 작성하는 부분.

                // 각 주소의 노출 규칙에 따라 주소를 조합한다.
                // 내려오는 변수가 값이 없는 경우엔 공백('')값을 가지므로, 이를 참고하여 분기 한다.
                var fullAddr = data.address; // 최종 주소 변수
                var extraAddr = ''; // 조합형 주소 변수

                // 기본 주소가 도로명 타입일때 조합한다.
                if(data.addressType === 'R'){
                    //법정동명이 있을 경우 추가한다.
                    if(data.bname !== ''){
                        extraAddr += data.bname;
                    }
                    // 건물명이 있을 경우 추가한다.
                    if(data.buildingName !== ''){
                        extraAddr += (extraAddr !== '' ? ', ' + data.buildingName : data.buildingName);
                    }
                    // 조합형주소의 유무에 따라 양쪽에 괄호를 추가하여 최종 주소를 만든다.
                    fullAddr += (extraAddr !== '' ? ' ('+ extraAddr +')' : '');
                }

                // 우편번호와 주소 정보를 해당 필드에 넣는다.
                $('[name="'+fieldId+'Postcode"]').val(data.zonecode);
                $('[name="'+fieldId+'Address1"]').val(fullAddr);

                // iframe을 넣은 element를 안보이게 한다.
                // (autoClose:false 기능을 이용한다면, 아래 코드를 제거해야 화면에서 사라지지 않는다.)
                $('#'+fieldId+'-daumPostcodeWrap').hide();

                // 우편번호 찾기 화면이 보이기 이전으로 scroll 위치를 되돌린다.
                document.body.scrollTop = currentScroll;
            },
            // 우편번호 찾기 화면 크기가 조정되었을때 실행할 코드를 작성하는 부분. iframe을 넣은 element의 높이값을 조정한다.
            onresize : function(size) {
                $('#'+fieldId+'-daumPostcodeWrap').css('height', size.height+'px');
            },
            width : '100%',
            height : '100%'
        }).embed($('#'+fieldId+'-daumPostcodeWrap')[0]);

        // iframe을 넣은 element를 보이게 한다.
        $('#'+fieldId+'-daumPostcodeWrap').show();
    }
</script>

<form class="start-payment" method="post" action="{{route('rich_shop.payment.start')}}">
    <input type="hidden" name="_token" value="{!! Session::token() !!}" />
    <div class="shop-container">
        <div class="cart-list">
            <ul>
                @foreach ($carts as $cart)
                    <li>
                        <div class="row">
                            <div class="xe-col-sm-3">
                                {{ $cart->product->productName }}
                            </div>
                            <div class="xe-col-sm-9">
                                @foreach ($cart->cartOptions as $cartOption)
                                    <ul>
                                        @if(in_array($cartOption->id, $cartOptionIds) === false)
                                            <!-- 카트에서 구매 안하는 항목들 -->
                                            <li class="option-box" style="background-color:red;">
                                                {{$cartOption->option->option_name}} /
                                                <span class="price">{{$cart->product->price + $cartOption->option->additional_price}}</span> 원 x
                                                <span class="quantity">{{$cartOption->quantity}}</span> 개
                                                =
                                                <span class="amount">{{($cart->product->price + $cartOption->option->additional_price) * $cartOption->quantity}}</span> 원
                                            </li>
                                        @else
                                            <li class="option-box">
                                                <input type="hidden" name="cartOptionId[]" value="{{$cartOption->id}}" />
                                                {{$cartOption->option->option_name}} /
                                                <span class="price">{{$cart->product->price + $cartOption->option->additinal_price}}</span> 원 x
                                                <span class="quantity">{{$cartOption->quantity}}</span> 개
                                                =
                                                <span class="amount">{{($cart->product->price + $cartOption->option->additinal_price) * $cartOption->quantity}}</span> 원
                                            </li>
                                        @endif
                                        </li>
                                    </ul>
                                @endforeach
                            </div>
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>

        <div class="summary">
            <div>
                <div>
                    상품가격 <span class="amount">{{number_format($summary['amount'])}}</span>
                    배송비 <span class="deliveryFee">0</span>
                </div>
                <div>
                    <span class="sum">{{number_format($summary['amount'] + $summary['deliveryFee'])}}</span>
                </div>
            </div>
        </div>

        <div class="shipping">
            <input type="hidden" name="shippingType" value="{{$selectedShippingInfo}}"/>
            <input type="hidden" name="shippingId" value=""/>
            <div class="dst-types">
                @foreach ($shippingInfos as $info)
                    <button type="button" class="info" data="{{$info->id}}">{{$info->title}}</button>
                @endforeach
                @if ($recentShippingInfo != null)
                <button type="button" class="recent">최근 배송지</button>
                @endif
                <button type="button" class="new">새로운 배송지</button>
            </div>
            @foreach ($shippingInfos as $info)
            <div class="shippingInfo" id="{{$info->id}}">
                <p>받는사람 : {{$info->recvName}}</p>
                <p>주소 : ({{$info->postcode}}) {{$info->address1}} {{$info->address2}}</p>
                <p>휴대전화 : {{$info->phone}}</p>
            </div>
            @endforeach
            @if ($recentShippingInfo != null)
            <div class="shippingInfo" id="recent">
                <p>받는사람 : {{$recentShippingInfo->recvName}}</p>
                <p>주소 : ({{$recentShippingInfo->postcode}}) {{$recentShippingInfo->address1}} {{$recentShippingInfo->address2}}</p>
                <p>휴대전화 : {{$recentShippingInfo->phone}}</p>
            </div>
            @endif
            <div class="shippingInfo" id="new">
                <p>받는사람 : <input type="text" name="recvName" value="" /></p>
                <p>주소 :

                    <input type="text" name="recvPostcode" placeholder="{{xe_trans('xe::postCode')}}" readonly="readonly" class="form-control">
                    <input type="button" onclick="execDaumPostcode2('shippingAddr')" value="{{xe_trans('xe::findPostCode')}}"><br>

                    <div id="shippingAddr-daumPostcodeWrap'}}" style="display:none;border:1px solid;width:500px;height:300px;margin:5px 0;position:relative">
                        <img src="//i1.daumcdn.net/localimg/localimages/07/postcode/320/close.png" id="btnFoldWrap" style="cursor:pointer;position:absolute;right:0px;top:-1px;z-index:1" onclick="foldDaumPostcode2('shippingAddr')" alt="{{xe_trans('xe::fold')}}">
                    </div>
                    <input type="text" name="recvAddress1" class="d_form large" placeholder="{{xe_trans('xe::address')}}" readonly="readonly">
                    <input type="text" name="recvAddress2" class="d_form large" placeholder="{{xe_trans('xe::detailedAddress')}}">


                </p>
                <p>휴대전화 : <input type="text" name="recvPhone" value="" /></p>
            </div>

            <p>배송 요구사항 : <input type="text" name="shoppingMemo" /></p>
        </div>

        <div class="payment">
            <div class="agreement">
                <input type="checkbox" name="agreement" value="1" />약관에 동의합니까?
            </div>

            <label><input type="radio" name="paymentType" value="{{Akasima\RichShop\Models\Order::PAYMENT_TYPE_CARD}}"> 카드?</label>
            <label><input type="radio" name="paymentType" value="{{Akasima\RichShop\Models\Order::PAYMENT_TYPE_CASH}}"> 현금?</label>

            <button type="submit" class="xe-btn-primary">결제하기</button>
        </div>
    </div>
</form>
