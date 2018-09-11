{{XeFrontend::css('/assets/vendor/bootstrap/css/bootstrap.css')->load()}}

{{XeFrontend::js([
$theme::asset('/assets/js/shop.js'),
'/assets/vendor/bootstrap/js/bootstrap.min.js',
])->load()}}

<div class="xe-shop category-item-wrap">
    <div class="container">
        <ol class="category-item">
            <li><a href="#">HOME</a><i class="xi-angle-right-thin"></i></li>
            <li>
                <div class="xe-dropdown ">
                    <button class="xe-btn" type="button" data-toggle="xe-dropdown xe-ellesis">CATEGORY 1</button>
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
                    <button class="xe-btn" type="button" data-toggle="xe-dropdown">2depth</button>
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


<style>
    .focus {border:1px solid red;}
</style>
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
                        <img class="photo" src="{{$product->getThumbnail(Akasima\RichShop\Skins\ShopSkin::Thumb1)->url()}}" />
                        <button type="button" class="xe-btn left"><i class="xi-angle-left-thin"><span class="xe-sr-only">이전 사진 보기</span></i></button>
                        <button type="button" class="xe-btn right"><i class="xi-angle-right-thin"><span class="xe-sr-only">다음 사진 보기</span></i></button>
                    </div>
                    <h3 class="xe-sr-only">상품 이미지 목록</h3>
                    <div class="product-img-list">
                        <ul>
                            <li class="focus"><a href="#"><img class="photo" src="{{$product->getThumbnail(Akasima\RichShop\Skins\ShopSkin::Thumb2)->url()}}" data-viewer-url="{{$product->getThumbnail(Akasima\RichShop\Skins\ShopSkin::Thumb1)->url()}}"/></a></li>
                            @foreach ($productDetailImageIds as $imageId)
                                <li><a href="#"><img class="photo" src="{{$product->getDetailImage($imageId, Akasima\RichShop\Skins\ShopSkin::Thumb2)->url()}}" data-viewer-url="{{$product->getDetailImage($imageId, Akasima\RichShop\Skins\ShopSkin::Thumb1)->url()}}"/></a></li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <div class="product-info select-option-container">
                    <h3 class="xe-sr-only">상품 정보</h3>

                    <!-- 아이콘 설정을 만들어야함 -->
                    @if (false)
                    <div class="label_wrap">
                        <span class="xe-shop-tag black">new</span>
                        <span class="xe-shop-tag">best</span>
                    </div>
                    @endif

                    <!-- [M] 줄넘김 어떻게 등록하도록 유도 할것인가? -->
                    <h3 class="product-info-title">{!! $product->productName !!}</h3>
                    <p class="product-info-title-caption">{!! $product->productSubName !!}</p>
                    <div class="product-info-container">

                        <!-- 가격을 표시하는 정책이 다양할것으로 생각됨 -->
                        @if (false)
                            <!-- 할인에 대한 어떤걸 만들어서 사용해야함 -->
                            <div class="product-info-low price">
                                <div class="product-info-cell">판매가</div>
                                <div class="product-info-cell"><p class="gray">{{number_format($product->price)}}<span>원</span></p></div>
                            </div>
                            <div class="product-info-low xe-border-bottom price">
                                <div class="product-info-cell">할인가</div>
                                <div class="product-info-cell"><p>{{number_format($product->price)}}<span>원</span></p></div>
                            </div>
                        @elseif (true)
                            <div class="product-info-low price">
                                <div class="product-info-cell">판매가</div>
                                <div class="product-info-cell">{{number_format($product->price)}}<span>원</span></div>
                            </div>
                        @endif

                        @if (false)
                                포인트 플러그인을 만들어야지 , XE1 마이그레이션할 때도 필요하고.. 기본으로 하나 해야할듯
                            <!-- [D] 보더 윗줄이 필요할 경우  xe-border-top 클래스 추가 부탁드립니다  -->
                            <div class="product-info-low xe-border-top">
                                <div class="product-info-cell">구매포인트</div>
                                <div class="product-info-cell"><p>100원</p></div>
                            </div>
                        @endif

                        @if (false)
                                혜택을 만들어야지 .. 혜택이 이벤트가 될런지.. 프로모션이 될런지..
                            <!-- [D] 보더 아래줄이 필요할 경우  xe-border-bottom 클래스 추가 부탁드립니다  -->
                            <div class="product-info-low xe-border-bottom">
                                <div class="product-info-cell">카드 혜택</div>
                                <div class="product-info-cell"><a href="#">자세히 보기 <i class="xi-angle-right-min"></i></a></div>
                            </div>
                        @endif

                        <!-- 배송방법에 대한 처리해야함 -->
                        <div class="product-info-low xe-border-top xe-border-bottom">
                            <div class="product-info-cell">배송비</div>
                            <div class="product-info-cell"><p>100원</p></div>
                        </div>

                        @if (false)
                            {{--이건 디자인 의도에 대해서 파악해야함--}}
                            <div class="product-info-low xe-border-bottom">
                                <div class="product-info-cell">카드 혜택</div>
                                <div class="product-info-cell">
                                    <div class="xe-select-box xe-btn" style="width:118px">
                                        <label>주문시 결제</label>
                                        <select>
                                            <option disabled="">Master</option>
                                            <option selected="selected">1</option>
                                            <option>2</option>
                                            <option>3</option>
                                            <option>4</option>
                                            <option>5</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        @endif

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
                                            <option disabled="" selected>선택</option>
                                            @foreach ($product->options as $option)
                                                <option value="{{$option->id}}" data-id="{{$option->id}}" data-price="{{$product->price + $option->additional_price}}">{{$option->option_name}} +{{ $option->additional_price }}</option>
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

<style>
    .detail-items {display:none;}
    .detail-items.focus {display:block;}
</style>
<div class="xe-shop detail">
    <div class="container">
        <div class="detail-wrap">
            <h2 class="xe-sr-only">상품 상세 보기</h2>
            <div class="detail-tab nav nav-tabs">
                <h3 class="xe-sr-only">상품 정보 탭</h3>
                <ul>
                    <li class="active"><a href="#detail-info" data-toggle="tab">상품정보</a></li>
                    <li><a href="#detail-talks" data-toggle="tab">구매평</a></li>
                    <li><a href="#detail-qna" data-toggle="tab">Q&amp;A</a></li>
                    <li><a href="#detail-as" data-toggle="tab">반품정보</a></li>
                </ul>
            </div>
            <div class="detail-container tab-content">
                <div class="detail-information tab-pane fade in active" id="detail-info">
                    <h3 class="xe-sr-only">상품 상세 정보</h3>
                    <div class="detail-information-table">
                        <div class="detail-information-row">
                            <div class="detail-information-cell th">상품상태</div>
                            <div class="detail-information-cell">{{$product->productTypeToText()}}</div>
                            <div class="detail-information-cell th">상품번호</div>
                            <div class="detail-information-cell">{{$product->product_code}}</div>
                        </div>
                        <div class="detail-information-row">
                            <div class="detail-information-cell th">제조사</div>
                            <div class="detail-information-cell">{{$product->product_id}}</div>
                            <div class="detail-information-cell th">브랜드</div>
                            <div class="detail-information-cell">{{$product->brand}}</div>
                        </div>
                        <div class="detail-information-row">
                            <div class="detail-information-cell th">모델명</div>
                            <div class="detail-information-cell">{{$product->product_model_name}}</div>
                            <div class="detail-information-cell th">원산지</div>
                            <div class="detail-information-cell">{{$product->product_origin}}</div>
                        </div>
                        <div class="detail-information-row">
                            <div class="detail-information-cell th">영수증 발급</div>
                            <div class="detail-information-cell">{{$product->reciept_info}}</div>
                            <div class="detail-information-cell th">A/S 안내</div>
                            <div class="detail-information-cell">{{$product->as}}</div>
                        </div>

                    </div>
                    <div class="detail-information-view">
                        <!-- [D] 상품 상세 정보 구역-->
                        {!! compile(\Akasima\RichShop\Plugin::getId(), $product->description, true) !!}
                    </div>
                </div>
                <div class="detail-information tab-pane fade" id="detail-talks">
                    <div class="detail-information-view">
                        {!! uio('comment', ['target' => $product]) !!}
                    </div>
                </div>
                <div class="detail-information tab-pane fade" id="detail-qna">
                    <div class="detail-information-view">
                        <!-- 다른 모델을 사용해서 댓글을 연결해야 할것인데.. 사용할 모델이 없음 .. -->
                        {!! uio('comment', ['target' => $qnaCommentUsable]) !!}
                    </div>
                </div>
                <div class="detail-information tab-pane fade" id="detail-as">
                    <div class="detail-information-view">반품 정보</div>
                </div>
            </div>
        </div>
    </div>
</div>
