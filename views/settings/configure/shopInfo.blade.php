<div class="row">
    <div class="col-sm-12">
        <div class="panel-group">
            <div class="panel">
                <div class="panel-heading">
                    <div class="pull-left">
                        <h3 class="panel-title">정보 등록(필수)</h3>
                    </div>
                </div>

                <form method="post" id="configure_form" action="{!! route('rich_shop.settings.configure.shopInfo.store', []) !!}">
                    <input type="hidden" name="_token" value="{!! Session::token() !!}" />
                    <div class="panel-collapse collapse in">
                        <div class="panel-body">

                            <div class="panel">

                                <div class="panel-heading">
                                    <div class="pull-left">
                                        <h4 class="panel-title">사업자 정보</h4>
                                    </div>
                                </div>
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <div class="clearfix">
                                                    <label>사업자등록번호 </label>
                                                </div>
                                                <input type="text" name="corpNo" class="form-control" value="{{ Request::old('corpNo', $config->get('corpNo')) }}" />
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <div class="clearfix">
                                                    <label>상호(법인명) </label>
                                                </div>
                                                <input type="text" name="corpName" class="form-control" value="{{ Request::old('corpName', $config->get('corpName')) }}" />
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <div class="clearfix">
                                                    <label>대표자 이름 </label>
                                                </div>
                                                <input type="text" name="repName" class="form-control" value="{{ Request::old('repName', $config->get('repName')) }}" />
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <div class="clearfix">
                                                    <label>영업소 소재지 주소 </label>
                                                </div>
                                                <input type="text" name="corpAddress" class="form-control" value="{{ Request::old('corpAddress', $config->get('corpAddress')) }}" />
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <div class="clearfix">
                                                    <label>대표 전화번호 </label>
                                                </div>
                                                <input type="text" name="repPhone" class="form-control" value="{{ Request::old('repPhone', $config->get('repPhone')) }}" />
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <div class="clearfix">
                                                    <label>대표 이메일 </label>
                                                </div>
                                                <input type="email" name="repEmail" class="form-control" value="{{ Request::old('repEmail', $config->get('repEmail')) }}" />
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <div class="clearfix">
                                                    <label>통신판매신고 번호 </label>
                                                </div>
                                                <input type="text" name="mailOrderBusinessNo" class="form-control" value="{{ Request::old('mailOrderBusinessNo', $config->get('mailOrderBusinessNo')) }}" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="panel">
                                <div class="panel-heading">
                                    <div class="pull-left">
                                        <h4 class="panel-title">고객센터 정보</h4>
                                    </div>
                                </div>
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <div class="clearfix">
                                                    <label>고객센터 전화번호 </label>
                                                </div>
                                                <input type="text" name="csPhone" class="form-control" value="{{ Request::old('csPhone', $config->get('csPhone')) }}" />
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <div class="clearfix">
                                                    <label>고객센터 이메일 </label>
                                                </div>
                                                <input type="text" name="csEmail" class="form-control" value="{{ Request::old('csEmail', $config->get('csEmail')) }}" />
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <div class="clearfix">
                                                    <label>운영시간 </label>
                                                </div>
                                                <textarea name="csRunTime" class="form-control" >{{ Request::old('csRunTime', $config->get('csRunTime')) }}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="panel">
                                <div class="panel-heading">
                                    <div class="pull-left">
                                        <h4 class="panel-title">개인 정보 책임자</h4>
                                    </div>
                                </div>
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <div class="clearfix">
                                                    <label>책임자 이름 </label>
                                                </div>
                                                <input type="text" name="cpoName" class="form-control" value="{{ Request::old('cpoName', $config->get('cpoName')) }}" />
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <div class="clearfix">
                                                    <label>책임자 전화번호 </label>
                                                </div>
                                                <input type="text" name="cpoPhone" class="form-control" value="{{ Request::old('cpoPhone', $config->get('cpoPhone')) }}" />
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <div class="clearfix">
                                                    <label>책임자 이메일 </label>
                                                </div>
                                                <input type="email" name="cpoEmail" class="form-control" value="{{ Request::old('cpoEmail', $config->get('cpoEmail')) }}" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="panel">
                                <div class="panel-heading">
                                    <div class="pull-left">
                                        <h4 class="panel-title">배송 정보</h4>
                                    </div>
                                </div>
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <div class="clearfix">
                                                    <label>반품 주소 </label>
                                                </div>
                                                <input type="text" name="cpoName" class="form-control" value="{{ Request::old('cpoName', $config->get('cpoName')) }}" />
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <div class="clearfix">
                                                    <label>반품 방법 </label>
                                                </div>
                                                <input type="text" name="cpoPhone" class="form-control" value="{{ Request::old('cpoPhone', $config->get('cpoPhone')) }}" />
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <div class="clearfix">
                                                    <label>기본 배송 요금 </label>
                                                </div>
                                                <input name="cpoEmail" class="form-control" value="{{ Request::old('cpoEmail', $config->get('cpoEmail')) }}" />
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
                </form>
            </div>
        </div>
    </div>
</div>