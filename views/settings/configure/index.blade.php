<div class="row">
    <div class="col-sm-12">
        <div class="panel-group">
            <div class="panel-heading">
                <div class="pull-left">
                    <h3 class="panel-title">쇼핑몰 기본 설정</h3>
                </div>
            </div>
        </div>

        <form method="post" id="configure_form" action="{!! route('rich_shop.settings.configure.store', []) !!}">
            <input type="hidden" name="_token" value="{!! Session::token() !!}" />
            <div class="panel-collapse collapse in">
                <div class="panel-body">
                    <div class="panel">

                        <div class="panel-heading">
                            <div class="pull-left">
                                <h4 class="panel-title">적립금</h4>
                            </div>
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <div class="clearfix">
                                            <label>회원가입 </label>
                                        </div>
                                        <input type="text" name="joinPoint" class="form-control" value="{{ Input::old('joinPoint', $config->get('joinPoint')) }}" /> Point
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <div class="clearfix">
                                            <label>로그인 </label>
                                        </div>
                                        <input type="text" name="loginPoint" class="form-control" value="{{ Input::old('loginPoint', $config->get('loginPoint')) }}" /> Point
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <div class="clearfix">
                                            <label>생일축하 </label>
                                        </div>
                                        <input type="text" name="birthdayPoint" class="form-control" value="{{ Input::old('birthdayPoint', $config->get('birthdayPoint')) }}" /> Point
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <div class="clearfix">
                                            <label>첫 구매 </label>
                                        </div>
                                        <input type="text" name="firstBuy" class="form-control" value="{{ Input::old('firstBuy', $config->get('firstBuy')) }}" /> % 할인
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <div class="clearfix">
                                            <label>상품 구매 </label>
                                        </div>
                                        <input type="text" name="buy" class="form-control" value="{{ Input::old('buy', $config->get('buy')) }}" /> % 할인
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="panel-heading">
                            <div class="pull-left">
                                <h4 class="panel-title">배송비</h4>
                            </div>
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <div class="clearfix">
                                            <label>배송비 사용 </label>
                                        </div>
                                        <select id="" name="useShippingFee" class="form-control">
                                            <option value="true" {!! $config->get('useShippingFee') == true ? 'selected="selected"' : '' !!} >{{xe_trans('xe::use')}}</option>
                                            <option value="false" {!! $config->get('useShippingFee') == false ? 'selected="selected"' : '' !!} >{{xe_trans('xe::disuse')}}</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <div class="clearfix">
                                            <label>기본 배송비 </label>
                                        </div>
                                        <input type="text" name="defaultShippingFee" class="form-control" value="{{ Input::old('defaultShippingFee', $config->get('defaultShippingFee')) }}" /> 원
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <div class="clearfix">
                                            <label>배송비 적용금액 </label>
                                        </div>
                                        <input type="text" name="shippingFreeAmount" class="form-control" value="{{ Input::old('shippingFreeAmount', $config->get('shippingFreeAmount')) }}" /> 원 미만
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
            </div>
        </form>
    </div>
</div>