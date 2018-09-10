{{XeFrontend::js('/plugins/rich_shop/assets/js/settings.js')->load()}}

<div class="row">
    <div class="col-sm-12">
        <div class="panel-group">
            <form method="post" action="{{route('rich_shop.settings.items.update', ['id' => $instanceId])}}">
                <input type="hidden" name="_token" value="{!! Session::token() !!}" />
                <input type="hidden" name="id" value="{{$instanceId}}" />
                <div class="panel">
                    <div class="panel-heading">
                        <div class="pull-left">
                            <h3 class="panel-title">사용자 페이지 설정 변경</h3>
                        </div>
                        <div class="pull-right">
                            <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne" class="btn-link panel-toggle pull-right"><i class="xi-angle-down"></i><i class="xi-angle-up"></i><span class="sr-only">{{xe_trans('fold')}}</span></a>
                        </div>
                    </div>

                    <div id="collapseOne" class="panel-collapse collapse in">
                        <div class="panel-body">

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
                                                    <label>상품 분류 선택</label>
                                                </div>

                                                <div class="xe-dropdown __xe-dropdown-form xe-rich-shop-category" data-relative="categoryItemId" data-item-nodes="{!! json_encode($selectedCategoryItem->getBreadcrumbs()) !!}">
                                                    <small class="categoryItemId xe-category-navigator"> </small>
                                                    <input type="hidden" class="categoryItemId" name="categoryItemId" value="{{$itemsConfig->get('categoryItemId')}}" />
                                                    <input type="hidden" class="categoryItemId-depth" name="categoryItemDepth" value="{{$itemsConfig->get('categoryItemDepth')}}" />
                                                    <button class="xe-btn select" type="button" data-toggle="xe-dropdown" aria-expanded="false">선택 </button>
                                                    <ul class="xe-dropdown-menu">
                                                        @foreach($categoryItems as $item)
                                                            <li><a href="#" data-url="{{route('manage.category.edit.item.children', ['id' => $item->category_id])}}" data-id="{{$item->id}}">{{xe_trans($item->word)}}</a></li>
                                                        @endforeach
                                                    </ul>
                                                </div>

                                                <div class="xe-category-children categoryItemId"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="panel-footer">
                        <div class="pull-right">
                            <button type="submit" class="btn btn-primary"><i class="xi-download"></i>{{xe_trans('xe::save')}}</button>
                        </div>
                    </div>
                </div>
            </form>

        </div>
    </div>
</div>