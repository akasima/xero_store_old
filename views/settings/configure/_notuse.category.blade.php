{{XeFrontend::js('/plugins/rich_shop/assets/js/settings.js')->load()}}

<div class="row">
    <div class="col-sm-12">
        <div class="panel-group">
            <div class="panel">
                <div class="panel-heading">
                    <div class="pull-left">
                        <h3 class="panel-title">카테고리 관리</h3>
                    </div>
                    <div class="pull-right">
                        <button class="xe-btn xe-btn-primary xe-add-category">카테고리 추가</button>
                    </div>
                </div>

                <div class="panel-collapse collapse in">
                    <div class="panel-body">


                        <div class="table-responsive">
                            <form class="__xe_form_list" method="post">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th scope="col">분류 이름</th>
                                        <th scope="col"></th>
                                        <th scope="col"></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($categories as $category)
                                        <tr>
                                            <td>{{$category->name}}</td>
                                            <td><a href="{{ route('manage.category.show', ['id' => $category->id]) }}" class="xe-btn" target="_blank">관리</a></td>
                                            <td>on / off ?</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="modal fade xe-category-modal">
    <div class="modal-dialog" data-toggle="modal">
        <form method="post" action="{{route('rich_shop.settings.configure.category.store')}}">
        <input type="hidden" name="_token" value="{!! Session::token() !!}" />
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">카테고리 추가</h4>
            </div>
            <div class="modal-body">
                <div class="input-group">
                    <span class="input-group-addon">카테고리 이름</span>
                    <input type="text" class="form-control" name="name" value="" required="required"/>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">생성</button>
            </div>
        </div><!-- /.modal-content -->
        </form>
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->