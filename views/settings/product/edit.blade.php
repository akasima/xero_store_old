<?php
    use Akasima\RichShop\Models\Product;
?>

{{XeFrontend::js('/plugins/rich_shop/assets/js/settings.js')->load()}}

<div class="row">
    <div class="col-sm-12">
        <div class="panel-group">
            <form method="post" action="{{route('rich_shop.settings.product.update', ['id' => $product->id])}}">
                <input type="hidden" name="_token" value="{!! Session::token() !!}" />
                <input type="hidden" name="id" value="{{$product->id}}" />
                <input type="hidden" name="slug" value="{{$product->slug->slug}}" />
                <div class="panel">
                    <div class="panel-heading">
                        <div class="pull-left">
                            <h3 class="panel-title">상품 수정</h3>
                        </div>
                        <div class="pull-right">
                            <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne" class="btn-link panel-toggle pull-right"><i class="xi-angle-down"></i><i class="xi-angle-up"></i><span class="sr-only">{{xe_trans('fold')}}</span></a>
                        </div>
                    </div>

                    <div id="collapseOne" class="panel-collapse collapse in">
                        <div class="panel-body">

                            <!-- 카테고리 / 다중카테고리 지원됨 -->
                            <div class="panel">
                                <div class="panel-heading">
                                    <div class="pull-left">
                                        <h4 class="panel-title">카테고리</h4>
                                    </div>
                                </div>
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <div class="clearfix">
                                                    <label>상품 분류 선택
                                                    </label>
                                                </div>

                                                <div class="xe-dropdown __xe-dropdown-form xe-rich-shop-category" data-relative="categoryItemId">
                                                    <div>
                                                        <small class="categoryItemId xe-category-navigator"> </small>
                                                        <button type="button" class="xe-btn xe-btn-sm category-add">추가</button>
                                                    </div>

                                                    <input type="hidden" class="categoryItemId" />
                                                    <button class="xe-btn select" type="button" data-toggle="xe-dropdown" aria-expanded="false">선택</button>
                                                    <ul class="xe-dropdown-menu">
                                                        <li><a href="#" data-id="">선택</a></li>
                                                        @foreach($categoryItems as $item)
                                                            <li><a href="#" data-url="{{route('manage.category.edit.item.children', ['id' => $item->category_id])}}" data-id="{{$item->id}}">{{xe_trans($item->word)}}</a></li>
                                                        @endforeach
                                                    </ul>
                                                </div>

                                                <div class="xe-category-children categoryItemId"></div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <ul class="categoryItemId xe-product-categories">
                                                @foreach ($productCategories as $productCategory)
                                                    <li>
                                                        <input type="hidden" name="categoryItemId[]" value="{{$productCategory->selected_item_id}}">
                                                        <small>
                                                            @foreach ($productCategory->getBreadcrumbs() as $crumbItem)
                                                                {{xe_trans($crumbItem->word)}} >
                                                            @endforeach
                                                        </small>
                                                        <button type="button" class="xe-btn-small destroy">X</button>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- EOF 카테고리 -->

                            <!-- 상태 -->
                            <div class="panel">
                                <div class="panel-heading">
                                    <div class="pull-left">
                                        <h4 class="panel-title">상태</h4>
                                    </div>
                                </div>
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <div class="clearfix">
                                                    <label>상품 노출 </label>
                                                </div>
                                                <select name="display" class="form-control">
                                                    <option value="{{ Product::DISPLAY_VISIBLE }}" @if($product->display == Product::DISPLAY_VISIBLE) selected="selected"@endif >보임</option>
                                                    <option value="{{ Product::DISPLAY_HIDDEN }}" @if($product->display == Product::DISPLAY_HIDDEN) selected="selected"@endif >숨김</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <div class="clearfix">
                                                    <label>판매 상태 </label>
                                                </div>
                                                <select name="sale" class="form-control">
                                                    <option value="{{ Product::SALE_ON }}" @if($product->sale == Product::SALE_ON) selected="selected" @endif >판매중</option>
                                                    <option value="{{ Product::SALE_CLOSE }}" @if($product->sale == Product::SALE_CLOSE) selected="selected" @endif >판매안함</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- EOF 상태 -->

                            <!-- 기본정보 -->
                            <div class="panel">
                                <div class="panel-heading">
                                    <div class="pull-left">
                                        <h4 class="panel-title">기본 정보</h4>
                                    </div>
                                </div>
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <div class="clearfix">
                                                    <label>상품 이름 </label>
                                                </div>
                                                <input type="text" name="product_name" class="form-control" value="{{ Request::old('productName', $product->product_name) }}" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <div class="clearfix">
                                                    <label>느낌있는 상품이름 <small>서브 네임</small></label>
                                                </div>
                                                <input type="text" name="product_sub_name" class="form-control" value="{{ Request::old('productSubName', $product->product_sub_name) }}" />
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <div class="clearfix">
                                                    <label>검색 키워드 <small>콤마로 구분해서 입력하세요.</small></label>
                                                </div>
                                                <input type="text" name="tags" class="form-control" value="{{ Request::old('tags', $product->tags) }}" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <div class="clearfix">
                                                    <label>공급사 상품명 <small>진짜 상품 이름 - 상품 스펙 출력 제조사, 상품이름 표시할 때 사용</small> </label>
                                                </div>
                                                <input type="text" name="product_real_name" class="form-control" value="{{ Request::old('productRealName', $product->product_real_name) }}" />
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <div class="clearfix">
                                                    <label>모델명 </label>
                                                </div>
                                                <input type="text" name="product_model_name" class="form-control" value="{{ Request::old('productModelName', $product->product_model_name) }}" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <div class="clearfix">
                                                    <label>상품코드 </label>
                                                </div>
                                                <input type="text" name="product_code" class="form-control" value="{{ Request::old('productCode', $product->product_code) }}" readonly="readonly"/>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <div class="clearfix">
                                                    <label>자체 상품코드 </label>
                                                </div>
                                                <input type="text" name="product_manage_code" class="form-control" value="{{ Request::old('productManageCode', $product->product_manage_code) }}" />
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <div class="clearfix">
                                                    <label>상품 상태 </label>
                                                </div>
                                                <select name="product_type" class="form-control">
                                                    <option value="{{$product::PRODUCT_TYPE_NEW}}" @if($product->sale == $product::PRODUCT_TYPE_NEW)selected="selected"@endif>신상품</option>
                                                    <option value="{{$product::PRODUCT_TYPE_USED}}" @if($product->sale == $product::PRODUCT_TYPE_USED)selected="selected"@endif>중고</option>
                                                    <option value="{{$product::PRODUCT_TYPE_RETURN}}" @if($product->sale == $product::PRODUCT_TYPE_RETURN)selected="selected"@endif>반품</option>
                                                    <option value="{{$product::PRODUCT_TYPE_REAPER}}" @if($product->sale == $product::PRODUCT_TYPE_REAPER)selected="selected"@endif>리퍼</option>
                                                    <option value="{{$product::PRODUCT_TYPE_DISPLAYED}}" @if($product->sale == $product::PRODUCT_TYPE_DISPLAYED)selected="selected"@endif>전시</option>
                                                    <option value="{{$product::PRODUCT_TYPE_SCRATCH}}" @if($product->sale == $product::PRODUCT_TYPE_SCRATCH)selected="selected"@endif>스크래치</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <div class="clearfix">
                                                    <label>상품 정보 </label>
                                                </div>
                                                <div class="write_form_editor">
                                                    {!! editor(\Akasima\RichShop\Plugin::getId(), [
                                                      'content' => Request::old('description', $product->description),
                                                      'contentDomName' => 'description',
                                                    ], $product->id) !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- EOF 기본정보 -->

                            <!-- 판매정보 -->
                            <div class="panel">
                                <div class="panel-heading">
                                    <div class="pull-left">
                                        <h4 class="panel-title">판매정보</h4>
                                    </div>
                                </div>
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <div class="clearfix">
                                                    <label>판매가 <small>고객이 상품을 구매하는 가격</small></label>
                                                </div>
                                                <input type="number" name="price" class="form-control" value="{{ Request::old('price', $product->price) }}" />
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <div class="clearfix">
                                                    <label>과세구분 </label>
                                                </div>
                                                <input type="radio" name="tax_type" value="{{$product::TEXT_TYPE_TAXABLE}}" @if($product->tax_type == $product::TEXT_TYPE_TAXABLE) checked="checked" @endif > 과세
                                                <input type="number" name="tax_rate" class="form-control" value="{{ Request::old('priceRate', $product->tax_rate) }}" /> %
                                                <input type="radio" name="tax_type" value="{{$product::TEXT_TYPE_EXEMPT}}" @if($product->tax_type == $product::TEXT_TYPE_EXEMPT) checked="checked" @endif > 면세
                                                <input type="radio" name="tax_type" value="{{$product::TEXT_TYPE_EXPORT}}" @if($product->tax_type == $product::TEXT_TYPE_EXPORT) checked="checked" @endif > 영세
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <div class="clearfix">
                                                    <label>공급가 <small>상품 구입가격</small></label>
                                                </div>
                                                <input type="text" name="supply_price" class="form-control" value="{{ Request::old('supplyPrice', $product->supply_price) }}" />
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <div class="clearfix">
                                                    <label>마진율 <small>판매가 계산식=공급가+(공급가*마진율)+추가금액(ex: 공급가30000+(공급가*마진율10%)+추가금액
                                                        </small></label>
                                                </div>
                                                <input type="text" name="margin_rate" class="form-control" value="{{ Request::old('marginRate', $product->margin_rate) }}" placeholder="마진율"/>
                                                <input type="text" name="margin_add_price" class="form-control" value="{{ Request::old('marginAddPrice', $product->margin_add_price) }}" placeholder="마진 추가 금액"/>
                                                <button type="button">판매가 적용</button>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <div class="clearfix">
                                                    <label>구매 수량 </label>
                                                </div>
                                                <input type="checkbox" name="buy_item_limit" value="1" @if($product->buy_item_limit == 1) checked="checked" @endif />제한없음
                                                <div class="buy-item-limit">
                                                    최소 구매수량 : <input type="text" name="buyItemLimitMin" class="form-control" value="{{ Request::old('buyItemLimitMin', $product->buy_item_limit_min) }}" />
                                                    최대 구매수량 : <input type="text" name="buyItemLimitMax" class="form-control" value="{{ Request::old('buyItemLimitMax', $product->buy_item_limit_max) }}" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- EOF 판매정보 -->

                            <!-- 이미지 -->
                            {{ XeFrontend::js([
                            'assets/vendor/jQuery-File-Upload/js/vendor/jquery.ui.widget.js',
                            'assets/vendor/jQuery-File-Upload/js/jquery.iframe-transport.js',
                            'assets/vendor/jQuery-File-Upload/js/jquery.fileupload.js',
                            ])->load() }}
                            {{ XeFrontend::css([
                            'assets/vendor/jQuery-File-Upload/css/jquery.fileupload.css',
                            'assets/vendor/jQuery-File-Upload/css/jquery.fileupload-ui.css',
                            ])->load() }}
                            <style>
                                .xe-rich-shop-imagebox {padding:1px; margin:1px;}
                                .xe-rich-shop-imagebox img {width:100%; height:100%}
                                .xe-rich-shop-imagebox,.drag {border:4px; border-color:red;}
                                .thumbnail-zone {display:none;}
                            </style>
                            <div class="panel">
                                <div class="panel-heading">
                                    <div class="pull-left">
                                        <h4 class="panel-title">이미지</h4>
                                    </div>
                                </div>
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <div class="clearfix">
                                                    <label>상품 이미지 등록 <small>대표 이미지를 등록하면 썸네일이 자동 생성됩니다.</small></label>
                                                </div>

                                                <div class="xe-rich-shop-product-image" data-url="{{route('rich_shop.settings.product.image.upload')}}" data-id="{{$product->productImageFile()->id}}">
                                                <div class="xe-rich-shop-product-image" data-url="{{route('rich_shop.settings.product.image.upload')}}">
                                                    <input type="hidden" name="product_image_file" value="" />
                                                    <a class="xe-btn xe-btn-primary __xe_inputBtn fileinput-button" >
                                                        <span>대표 이미지 등록 (어떤크기 ? 500px * 500px)</span>
                                                        <input type="file"/>
                                                    </a>
                                                </div>

                                                <div class="row thumbnail-zone">
                                                    @foreach (app('xe.rich.shop.product')->getThumbnailDimensions() as $code => $size)
                                                        <div class="col-sm-2 xe-rich-shop-imagebox dropZone" data-url="{{route('rich_shop.settings.product.thumbnail.upload', ['code' => $code])}}" data-code="{{$code}}" data-id="{{$product->productImageFile()->id}}">

                                                            <p>{{$code}} px</p>
                                                            @if ($productPhotoThumbnail = $product->getThumbnail($code))
                                                                <img class="preview" src="{{$productPhotoThumbnail->url()}}"/>
                                                            @else
                                                                <img class="preview" src="http://placehold.it/{{$code}}"/>
                                                            @endif
                                                            <a class="xe-btn xe-btn-primary xe-btn-sm __xe_inputBtn fileinput-button">
                                                                <span>변경</span>
                                                                <input type="file"/>
                                                            </a>
                                                        </div>
                                                    @endforeach

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <div class="clearfix">
                                                    <label>추가 이미지 등록 (마우스를 드래그해서 순서 변경 가능)</label>
                                                </div>
                                                <div class="xe-rich-shop-product-detail-image" data-url="{{route('rich_shop.settings.product.image.upload')}}">
                                                    <input type="hidden" name="product_image_file" value="" />
                                                    <a class="xe-btn xe-btn-primary __xe_inputBtn fileinput-button" >
                                                        <span>상세 이미지 등록 (어떤크기 ? 500px * 500px)</span>
                                                        <input type="file"/>
                                                    </a>
                                                </div>

                                                <!-- 여기 담기는 이미지들 순서 변경 가능해야하고. 이걸 기록해야함 -->
                                                <div class="row thumbnail-detail-zone">
                                                    @foreach ($productDetailImageIds as $imageId)
                                                    <div class="col-sm-2">
                                                        @if ($productDetailThumbnail = $product->getDetailImage($imageId))
                                                            <img src="{{$productDetailThumbnail->url()}}"/>
                                                            <input type="hidden" name="product_detail_image_file[]" value="{{$imageId}}" />
                                                        @else
                                                            <img src="http://placehold.it/200x200"/>
                                                        @endif
                                                    </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- EOF 이미지 -->

                            <!-- 이용안내 global 설정으로 등록한 내용이 보여지고. 추가(새로작성)할 수 있다-->
                            <div class="panel">
                                <div class="panel-heading">
                                    <div class="pull-left">
                                        <h4 class="panel-title">이용안내</h4>
                                    </div>
                                </div>
                                <div class="panel-body">
                                    <div class="row">

                                    </div>

                                </div>
                            </div>
                            <!-- EOF 이용안내 -->
                        </div>
                    </div>
                    <div class="panel-footer">
                        <div class="pull-right">
                            <button type="submit" class="btn btn-primary"><i class="xi-download"></i>{{xe_trans('xe::save')}}</button>
                        </div>
                    </div>
                </div>
            </form>

            <div class="panel">
                <div class="panel-heading">
                    <div class="pull-left">
                        <h3 class="panel-title">옵션 관리</h3>
                    </div>
                    <div class="pull-right">
                        <a data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" class="btn-link panel-toggle pull-right"><i class="xi-angle-down"></i><i class="xi-angle-up"></i><span class="sr-only">{{xe_trans('fold')}}</span></a>
                    </div>
                </div>

                <div id="collapseTwo" class="panel-collapse collapse in">
                    <div class="panel-body">
                        <div class="panel">

                            <div class="panel-heading">
                                <div class="pull-left">
                                    <h4 class="panel-title">옵션</h4>
                                </div>
                                <div class="pull-right">
                                    <button type="button" class="xe-add-option xe-btn xe-btn-primary">추가</button>
                                </div>
                            </div>
                            <div class="panel-body">
                                <div class="table-responsive xe-optoin-list">
                                    <form class="__xe_form_list" method="post">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <table class="table">
                                            <thead>
                                            <tr>
                                                <th scope="col">옵션 이름</th>
                                                <th scope="col">추가 금액</th>
                                                <th scope="col">재고 수량</th>
                                                <th scope="col">-</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach ($product->options as $option)
                                                <tr>
                                                    <td>{{$option->option_name}}</td>
                                                    <td>{{$option->additional_price}}</td>
                                                    <td>{{$option->stock_quantity}}</td>
                                                    <td>
                                                        <button type="button" class="xe-btn-sm xe-edit-option" data-id="{{$option->id}}" data-action="{{route('rich_shop.settings.product.option.update', ['id' => $option->id, 'productId' => $option->product_id])}}">관리</button>
                                                    </td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal fade xe-option-modal">
                        <div class="modal-dialog" data-toggle="modal">
                            <form method="post" action="{{route('rich_shop.settings.product.option.store', ['productId' => $product->id])}}">
                                <input type="hidden" name="_token" value="{!! Session::token() !!}" />
                                <input type="hidden" name="product_id" value="{{$product->id}}" />
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                        <h4 class="modal-title">옵션 추가</h4>
                                    </div>
                                    <div class="modal-body">
                                        <div class="input-group">
                                            <span class="input-group-addon">옵션 이름</span>
                                            <input type="text" class="form-control" name="option_name" value="" required="required"/>
                                        </div>
                                        <div class="input-group">
                                            <span class="input-group-addon">추가 금액</span>
                                            <input type="text" class="form-control" name="additional_price" value="0" required="required"/>
                                        </div>
                                        <div class="input-group">
                                            <span class="input-group-addon">재고 수량</span>
                                            <input type="text" class="form-control" name="stock_quantity" value="0" required="required"/>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-primary">추가</button>
                                    </div>
                                </div><!-- /.modal-content -->
                            </form>
                        </div><!-- /.modal-dialog -->
                    </div><!-- /.modal -->

                    <div class="modal fade xe-option-edit-modal">
                        <div class="modal-dialog" data-toggle="modal">
                            <form method="post" action="">
                                <input type="hidden" name="_token" value="{!! Session::token() !!}" />
                                <input type="hidden" name="id" value="" />
                                <input type="hidden" name="product_id" value="{{$product->id}}" />
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                        <h4 class="modal-title">옵션 관리</h4>
                                    </div>
                                    <div class="modal-body">
                                        <div class="input-group">
                                            <span class="input-group-addon">옵션 이름</span>
                                            <input type="text" class="form-control" name="option_name" value="" required="required"/>
                                        </div>
                                        <div class="input-group">
                                            <span class="input-group-addon">추가 금액</span>
                                            <input type="text" class="form-control" name="additional_price" value="" required="required"/>
                                        </div>
                                        <div class="input-group">
                                            <span class="input-group-addon">재고 수량</span>
                                            <input type="text" class="form-control" name="stock_quantity" value="" required="required"/>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-primary">수정</button>
                                    </div>
                                </div><!-- /.modal-content -->
                            </form>
                        </div><!-- /.modal-dialog -->
                    </div><!-- /.modal -->

                </div>
            </div>

        </div>
    </div>
</div>