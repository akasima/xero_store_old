<div class="row">
    <div class="col-sm-12">
        <div class="panel-group">
            <div class="panel">
                <div class="panel-heading">
                    <div class="pull-left">
                        <h3 class="panel-title">전체 내역</h3>
                    </div>
                 </div>

                <div class="panel-heading">

                </div>


                <div class="table-responsive">
                    <form class="__xe_form_list" method="post">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <table class="table">
                            <thead>
                            <tr>
                                <th scope="col"><input type="checkbox" class="__xe_check_all"></th>
                                <th scope="col">주문일시</th>
                                <th scope="col">주문번호</th>
                                <th scope="col">주문자 이름<br/>(수령자 이름)</th>
                                <th scope="col">회원 ID</th>
                                <th scope="col">이미지</th>
                                <th scope="col">주문상품</th>
                                <th scope="col">총 수량</th>
                                <th scope="col">총 주문 금액<br>(결제 금액)</th>
                                <th scope="col">주문 상태<br/>(결제 수단)</th>
                                <th scope="col">개별 설정</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($orders as $order)
                                <tr>
                                    <td><input type="checkbox" name="id[]" class="__xe_checkbox" value="{{ $order->id }}"></td>
                                    <td><a href="/{{ $urls[$document->instanceId] }}/show/{{ $document->id }}" target="_blank"><strong>[{{ $urls[$document->instanceId] }}]</strong> {{ $document->title }}<i class="xi-new"></i><i class="xi-external-link"></i></a></td>
                                    <td><a href="#">{{ $document->writer }}</a></td>
                                    <td>{{ $document->assentCount }}/{{ $document->readCount }}</td>
                                    <td><a href="#">{{ $document->createdAt }}</a></td>
                                    <td><a href="#">{{ $document->ipaddress }}</a></td>
                                    <td><span class="label label-green">{{ $document->display }}</span></td>
                                    <td><span class="label label-grey">{{ $document->approved }}</span></td>
                                </tr>
                            </tbody>
                            @endforeach
                        </table>
                    </form>
                </div>

                <div class="panel-footer">
                    <div class="pull-left">
                        <nav>
                            {{--{!! $orders->render() !!}--}}
                        </nav>
                    </div>
                </div>




            </div>
        </div>
    </div>
</div>