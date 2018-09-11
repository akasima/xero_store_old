{{ XeFrontend::js($theme::asset('js/settings.js'))->load() }}

<div class="row">
    <div class="col-sm-12">
        <div class="panel-group">
            <div class="panel">
                <div class="panel-heading">
                    <div class="pull-left">
                        <h3 class="panel-title">상품 목록 관리</h3>
                    </div>
                </div>

                <div class="panel-heading">
                    <div class="pull-left">
                        <form>
                            <div class="input-group search-group">
                                <div class="input-group-btn __xe_btn_search_target">
                                    <input type="hidden" name="search_target" value="{{ Request::get('search_target') }}">
                                    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><span class="__xe_text">{{Request::has('search_target') && Request::get('search_target') != '' ? xe_trans('board::' . Request::get('search_target')) : xe_trans('xe::select')}}</span> <span class="caret"></span></button>

                                    <ul class="dropdown-menu" role="menu">
                                        <li @if(Request::get('search_target') == '') class="active" @endif value=""><a href="#" value="">{{xe_trans('xe::select')}}</a></li>
                                        <li @if(Request::get('search_target') == 'titleAndContent') class="active" @endif value="titleAndContent"><a href="#" value="titleAndcontent">{{xe_trans('board::titleAndContent')}}</a></li>
                                        <li @if(Request::get('search_target') == 'title') class="active" @endif value="title"><a href="#" value="title">{{xe_trans('board::title')}}</a></li>
                                        <li @if(Request::get('search_target') == 'content') class="active" @endif value="content"><a href="#" value="content">{{xe_trans('board::content')}}</a></li>
                                        <li @if(Request::get('search_target') == 'writer') class="active" @endif value="writer"><a href="#" value="writer">{{xe_trans('board::writer')}}</a></li>
                                    </ul>
                                </div>
                                <div class="search-input-group">
                                    <input type="text" name="searchKeyword" class="form-control" aria-label="Text input with dropdown button" placeholder="{{xe_trans('xe::enterKeyword')}}" value="{{Request::get('searchKeyword')}}">
                                </div>
                            </div>

                            <div class="input-group search-group">
                                <div>
                                    <input type="checkbox" class="form-control" name="withLowerCategoryItem" value="1" @if(Request::has('withLowerCategoryItem')) checked="checked" @endif>
                                    <span>하위분류 포함</span>
                                </div>
                                <div class="xe-dropdown __xe-dropdown-form xe-rich-shop-category" data-relative="categoryItemId" data-item-nodes="{!! json_encode($selectedCategoryItem->getBreadcrumbs()) !!}">
                                    <input type="hidden" class="categoryItemId" name="categoryItemId" value="{{Request::get('categoryItemId')}}" />
                                    <input type="hidden" class="categoryItemId-depth" name="categoryItemDepth" value="{{Request::get('categoryItemDepth')}}" />
                                    <button class="xe-btn" type="button" data-toggle="xe-dropdown" aria-expanded="false">선택 </button>
                                    <ul class="xe-dropdown-menu">
                                        @foreach($categoryItems as $categoryItem)
                                            <li><a href="#" data-url="{{route('manage.category.edit.item.children', ['id' => $categoryItem->category_id])}}" data-id="{{$categoryItem->id}}">{{xe_trans($categoryItem->word)}}</a></li>
                                        @endforeach
                                    </ul>
                                </div>
                                <div class="xe-category-children categoryItemId"></div>
                            </div>

                            <div class="input-group search-group xe-period">
                                <div class="xe-form-group">
                                    {!! uio('uiobject/board@select', [
                                        'name' => 'period',
                                        'label' => xe_trans('xe::select'),
                                        'value' => Request::get('period'),
                                        'items' => $periods,
                                    ]) !!}
                                </div>
                                <div class="xe-form-inline dates">
                                    <input type="text" name="startCreatedAt" class="xe-form-control" title="{{xe_trans('board::startDate')}}" value="{{Request::get('startCreatedAt')}}"> - <input type="text" name="endCreatedAt" class="xe-form-control" title="{{xe_trans('board::endDate')}}" value="{{Request::get('endCreatedAt')}}">
                                </div>
                            </div>

                            <div class="input-group search-group">
                                {!! uio('uiobject/board@select', [
                                    'name' => 'display',
                                    'label' => xe_trans('xe::select'),
                                    'value' => Request::get('display'),
                                    'items' => [
                                        ['value' => Akasima\RichShop\Models\Product::DISPLAY_HIDDEN, 'text' => '숨김',],
                                        ['value' => Akasima\RichShop\Models\Product::DISPLAY_VISIBLE, 'text' => '보기',],
                                    ],
                                ]) !!}

                                {!! uio('uiobject/board@select', [
                                    'name' => 'sale',
                                    'label' => xe_trans('xe::select'),
                                    'value' => Request::get('sale'),
                                    'items' => [
                                        ['value' => Akasima\RichShop\Models\Product::SALE_CLOSE, 'text' => '판매안함',],
                                        ['value' => Akasima\RichShop\Models\Product::SALE_ON, 'text' => '판매중',],
                                    ],
                                ]) !!}
                            </div>
                            <button type="submit" class="xe-btn xe-btn-primary">{{xe_trans('xe::search')}}</button>
                        </form>
                    </div>
                </div>

                <div class="panel-body">
                    <div class="table-responsive">
                        <form class="__xe_form_list" method="post">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <table class="table">
                                <thead>
                                <tr>
                                    <th scope="col">상품 아이디</th>
                                    <th scope="col">상품 이름</th>
                                    <th scope="col">판매 가격</th>
                                    <th scope="col">출력 상태</th>
                                    <th scope="col">판매 상태</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($paginate as $product)
                                    <tr>
                                        <td>{{$product->id}}</td>
                                        <td>
                                            {!! $product->image()->render(['width' => 50, 'height' => 50]) !!}
                                            <a href="{{route('rich_shop.settings.product.edit', ['id' => $product->id])}}">{{$product->product_name}}</a>
                                        </td>
                                        <td>{{$product->price}}</td>
                                        <td>{{ $product->displayTypeToText() }}</td>
                                        <td>{{ $product->saleTypeToText() }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </form>
                    </div>
                </div>

                <div class="panel-footer">
                    <div class="pull-left">
                        <nav>
                            {!! $paginate->render() !!}
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
