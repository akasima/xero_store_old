{{XeFrontend::js($theme::asset('js/shop.js'))->load()}}

<div class="xe-shop category-item-wrap">
    <div class="container">
        <ol class="category-item">
            <li><a href="#">HOME</a><i class="xi-angle-right-thin"></i></li>
            <li>
                <div class="xe-dropdown ">
                    <button class="xe-btn" type="button" data-toggle="xe-dropdown xe-ellesis">CATEGORYCATEGORYCATEGORY 1</button>
                    <ul class="xe-dropdown-menu">
                        @foreach($breadcrumbs as $item)
                            <li><a href="#">{{xe_trans($item->word)}}</a></li>
                        @endforeach
                    </ul>
                </div>
                <i class="xi-angle-right-thin"></i>
            </li>
            <li>
                <div class="xe-dropdown ">
                    <button class="xe-btn" type="button" data-toggle="xe-dropdown">2dqpth</button>
                    <ul class="xe-dropdown-menu">
                        <li><a href="#">text</a></li>
                        <li><a href="#">text</a></li>
                    </ul>
                </div>
            </li>
            <li>
                <a href="{{ route('rich_shop.settings.product.edit', ['id'=>$product->id]) }}" target="_blank"><span class="xe-sr-only">{{ xe_trans('xe::manage') }}</span><i class="xi-cog"></i></a>
            </li>
        </ol>
    </div>
</div>



<div class="xe-shop product">
    <div class="container">
        <form method="post" action="{{route('rich_shop.order')}}">
            <input type="hidden" name="_token" value="{{{ Session::token() }}}" />
            <input type="hidden" name="product_id" value="{{$product->id}}" />

            <div class="product-wrap">
                <h2 class="xe-sr-only">상품 관련 정보</h2>
                <div class="product-img">
                    <h3 class="xe-sr-only">상품 이미지</h3>
                    <div class="product-img-view">
                        {!! $product->getThumbnail(Akasima\RichShop\Skins\ShopSkin::Thumb1)->render() !!}
                        <button class="xe-btn left"><i class="xi-angle-left-thin"><span class="xe-sr-only">이전 사진 보기</span></i></button>
                        <button class="xe-btn right"><i class="xi-angle-right-thin"><span class="xe-sr-only">다음 사진 보기</span></i></button>
                    </div>
                    <h3 class="xe-sr-only">상품 이미지 목록</h3>
                    <div class="product-img-list">
                        <ul>
                            <li><a href="#"><img src="/plugins/rich_shop/assets/img/tmp_product.jpg" alt=""></a></li>
                            <li><a href="#"><img src="/plugins/rich_shop/assets/img/tmp_product.jpg" alt=""></a></li>
                            <li><a href="#"><img src="/plugins/rich_shop/assets/img/tmp_product.jpg" alt=""></a></li>
                            <li><a href="#"><img src="/plugins/rich_shop/assets/img/tmp_product.jpg" alt=""></a></li>
                            <li><a href="#"><img src="/plugins/rich_shop/assets/img/tmp_product.jpg" alt=""></a></li>
                            <li><a href="#"><img src="/plugins/rich_shop/assets/img/tmp_product.jpg" alt=""></a></li>
                            <li><a href="#"><img src="/plugins/rich_shop/assets/img/tmp_product.jpg" alt=""></a></li>
                            <li><a href="#"><img src="/plugins/rich_shop/assets/img/tmp_product.jpg" alt=""></a></li>
                            <li><a href="#"><img src="/plugins/rich_shop/assets/img/tmp_product.jpg" alt=""></a></li>
                            <li><a href="#"><img src="/plugins/rich_shop/assets/img/tmp_product.jpg" alt=""></a></li>
                            <li><a href="#"><img src="/plugins/rich_shop/assets/img/tmp_product.jpg" alt=""></a></li>
                        </ul>
                    </div>
                </div>
                <div class="product-info select-option-container">
                    <h3 class="xe-sr-only">상품 정보</h3>
                    <div class="label_wrap">
                        <span class="xe-shop-tag black">new</span>
                        <span class="xe-shop-tag">best</span>
                    </div>
                    <!-- [M] 줄넘김 어떻게 등록하도록 유도 할것인가? -->
                    <h3 class="product-info-title">{!! $product->productName !!}</h3>
                    <p class="product-info-title-caption">{!! $product->productName !!}</p>
                    <div class="product-info-container">
                        <div class="product-info-low price">
                            <div class="product-info-cell">판매가</div>
                            <div class="product-info-cell"><p class="gray">{{number_format($product->price)}}<span>원</span></p></div>
                        </div>
                        <div class="product-info-low xe-border-bottom price">
                            <div class="product-info-cell">할인가</div>
                            <div class="product-info-cell"><p>{{number_format($product->price)}}<span>원</span></p></div>
                        </div>

                        {{--<!-- [D] 보더 윗줄이 필요할 경우  xe-border-top 클래스 추가 부탁드립니다  -->--}}
                        {{--<div class="product-info-low xe-border-top">--}}
                            {{--<div class="product-info-cell">구매포인트</div>--}}
                            {{--<div class="product-info-cell"><p>100원</p></div>--}}
                        {{--</div>--}}
                        {{--<!-- [D] 보더 아래줄이 필요할 경우  xe-border-bottom 클래스 추가 부탁드립니다  -->--}}
                        {{--<div class="product-info-low xe-border-bottom">--}}
                            {{--<div class="product-info-cell">카드 혜택</div>--}}
                            {{--<div class="product-info-cell"><a href="#">자세히 보기 <i class="xi-angle-right-min"></i></a></div>--}}
                        {{--</div>--}}

                        <div class="product-info-low xe-border-top xe-border-bottom">
                            <div class="product-info-cell">배송비</div>
                            <div class="product-info-cell"><p>100원</p></div>
                        </div>
                        {{--<div class="product-info-low xe-border-bottom">--}}
                            {{--<div class="product-info-cell">카드 혜택</div>--}}
                            {{--<div class="product-info-cell">--}}
                                {{--<div class="xe-select-box xe-btn" style="width:118px">--}}
                                    {{--<label>주문시 결제</label>--}}
                                    {{--<select>--}}
                                        {{--<option disabled="">Master</option>--}}
                                        {{--<option selected="selected">1</option>--}}
                                        {{--<option>2</option>--}}
                                        {{--<option>3</option>--}}
                                        {{--<option>4</option>--}}
                                        {{--<option>5</option>--}}
                                    {{--</select>--}}
                                {{--</div>--}}
                            {{--</div>--}}
                        {{--</div>--}}
                    </div>
                    <div class="options product-info-container">
                        <!-- [D] 굵은보더 윗줄이 필요할 경우  xe-border-top-bold 클래스 추가 부탁드립니다  -->
                        @if (count($product->options) > 1)
                            <div class="product-info-low xe-border-top xe-border-bottom option">
                                <div class="product-info-cell">옵션</div>
                                <div class="product-info-cell">
                                    <div class="xe-select-box xe-btn">
                                        <label>옵션을 선택하세요</label>
                                        <select class="xe-select-option">
                                            <option disabled="">선택</option>
                                            @foreach ($product->options as $option)
                                                <option value="{{$option->id}}" data-id="{{$option->id}}" data-price="{{$product->price + $option->additional_price}}">{{$option->option_name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                    <div class="selected product-info-container">
                        @if (count($product->options) == 1)
                            <div class="product-info-low xe-border-top xe-border-bottom counter">
                                <div class="product-info-cell">{{$product->options[0]->option_name}}</div>
                                <div class="product-info-cell">
                                    <input type="hidden" name="orderOptionId[]" value="{{$product->options[0]->id}}" />
                                    <input type="hidden" name="orderQuantity[]" value="1" />
                                    <input type="hidden" name="orderPrice[]" value="{{$product->price + $product->options[0]->additional_price}}" />
                                    <div class="xe-spin-box">
                                        <button type="button" class="decrease"><i class="xi-minus-thin"></i><span class="xe-sr-only">감소</span></button>
                                        <p class="orderQuantity">1</p>
                                        <button type="button" class="increase"><i class="xi-plus-thin"></i><span class="xe-sr-only">증가</span></button>
                                    </div>
                                    <p><span class="price" data-price="{{$product->price + $product->options[0]->additional_price}}">{{$product->price + $product->options[0]->additional_price}}</span>원</p>
                                    <button type="button" class="xe-btn xe-btn-remove"><i class="xi-close-thin"></i><span class="xe-sr-only">이 옵션 삭제</span></button>
                                </div>
                            </div>
                        @endif
                    </div>
                    <div class="amount product-info-container" @if (count($product->options) != 1) style="display:none;" @endif>
                        <div class="product-info-low totalprice ">
                            <div class="product-info-cell">총 합계 금액</div>
                            <div class="product-info-cell">
                                (<span class="quantity">1</span>개)
                                <p><span class="price">{{$product->price}}</span><span>원</span></p>
                            </div>
                        </div>
                    </div>

                    <div class="btn-buy-wrap">
                        <button type="submit" class="xe-btn xe-btn-buy">구매하기</button>
                        <button type="button" class="xe-btn xe-add-cart" data-url="{{route('rich_shop.cart.store')}}">장바구니</button>
                        <button type="button" class="xe-btn">찜하기</button>
                    </div>

                </div>
            </div>
        </form>
    </div>
</div>

<div class="xe-shop detail">
    <div class="container">
        <div class="detail-wrap">
            <h2 class="xe-sr-only">상품 상세 보기</h2>
            <div class="detail-tab">
                <h3 class="xe-sr-only">상품 정보 탭</h3>
                <ul>
                    <li class="active"><a href="#">상품정보</a></li>
                    <li><a href="#">구매평</a></li>
                    <li><a href="#">Q&amp;A</a></li>
                    <li><a href="#">반품정보</a></li>
                </ul>
            </div>
            <div class="detail-container">
                <div class="detail-information">
                    <h3 class="xe-sr-only">상품 상세 정보</h3>
                    <div class="detail-information-table">
                        <div class="detail-information-row">
                            <div class="detail-information-cell th">상품상태</div>
                            <div class="detail-information-cell">상품상태 상품상태상품상태상품상태상품상태상품상태  상품상태 상품상태 상품상태 상품상태 상품상태 상품상태 상품상태 상품상태 상품상태 상품상태 상품상태</div>
                            <div class="detail-information-cell th">상품번호</div>
                            <div class="detail-information-cell">상품번호 상품번호 상품번호</div>
                        </div>
                        <div class="detail-information-row">
                            <div class="detail-information-cell th">제조사</div>
                            <div class="detail-information-cell"></div>
                            <div class="detail-information-cell th">브랜드</div>
                            <div class="detail-information-cell"></div>
                        </div>
                        <div class="detail-information-row">
                            <div class="detail-information-cell th">모델명</div>
                            <div class="detail-information-cell"></div>
                            <div class="detail-information-cell th">원산지</div>
                            <div class="detail-information-cell"></div>
                        </div>
                        <div class="detail-information-row">
                            <div class="detail-information-cell th">영수증 발급</div>
                            <div class="detail-information-cell"></div>
                            <div class="detail-information-cell th">A/S 안내</div>
                            <div class="detail-information-cell"></div>
                        </div>

                    </div>
                    <div class="detail-information-view">
                        <!-- [D] 상품 상세 정보 구역-->
                        {!! compile(\Akasima\RichShop\Plugin::getId(), $product->description, true) !!}
                    </div>
                </div>
                <!-- <div class="detail-talks"></div> -->
                <!-- <div class="detail-qna"></div> -->
                <!-- <div class="detail-as"></div> -->
            </div>
        </div>
    </div>
</div>
