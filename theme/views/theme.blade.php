{{-- script --}}
{{ app('xe.frontend')->js([
    'plugins/rich_shop/assets/js/markup.js'
])->load() }}

{{-- stylesheet --}}
{{ app('xe.frontend')->css([
    'plugins/rich_shop/assets/css/user/layout.css',
])->load() }}

<h1 class="xe-sr-only">xe 쇼핑</h1>
<section class="xe-shop header">
    <!-- [D] width를 제어하기 위해 사용하는 클래스입니다. 100% 넓이를 사용하는 위젯은 사용할 필요가 없습니다. -->
    <div class="container">
        <h2 class="xe-sr-only">상단 유틸</h2>
        <article class="xe-shop-notice">
            <h3>notice</h3>
            <div class="notice-view">
                <ul>
                    <li><a href="#">1.테스트 베이스 입니다 테스트 베이스 입니다 </a></li>
                    <li><a href="#">2.공지사항입니다.></a></li>
                    <li><a href="#">3.테스트 베이스 입니다 테스트 베이스 입니다 테스트 베이스 입니다 </a></li>
                    <li><a href="#">4.테스트 베이스 입니다 테스트 베이스 입니다 테스트 베이스 입니다 </a></li>
                </ul>
            </div>
        </article>
        <article class="xe-shop-utilmenu">
            <h2 class="xe-sr-only">관련 링크</h2>
            @if(auth()->check())
                <ul class="xe-shop-utilmenu-list">
                    @if(auth()->user()->isAdmin())
                        <li><a href="{{ route('settings') }}" class="plugin" target="_blank"><i class="xi-cog"></i></a></li>
                    @endif
                    <li><a href="{{ route('rich_shop.cart') }}">장바구니</a></li>
                    <li><a href="#">주문조회</a></li>
                    <li><a href="{{ route('user.settings') }}">마이 페이지</a></li>
                    <li><a href="{{ route('logout') }}">로그아웃</a></li>
                </ul>
            @else
                <ul class="xe-shop-utilmenu-list">
                    <li><a href="{{ route('login') }}">로그인</a></li>
                    <li><a href="{{ route('auth.register') }}">회원가입</a></li>
                </ul>
            @endif

            <h2 class="xe-sr-only">검색</h2>
            <div class="xe-shop-search">
                <input type="text" class="xe-form-control" placeholder="">
                <button type="submit"><i class="xi-search"></i></button>
            </div>
        </article>
    </div>
</section>
<section class="xe-shop logo">
    <div class="container">
        <h2 class="xe-shop-logo">
            <a href="/"><img src="/plugins/rich_shop/assets/img/shop-logo@lg.png" alt="쇼핑몰 로고"></a>
        </h2>
    </div>
</section>
<section class="xe-shop menu">
    <div class="container">
        @include($theme::view('gnb'))
    </div>
</section>

<section class="contents">
    {!! $content !!}
</section>

<section class="xe-shop guide">
    <div class="container">
        <h2 class="xe-sr-only">회사 기본정보</h2>
        <article class="xe-shop-link">
            <h3 class="xe-sr-only">관련링크</h3>
            <ul>
                <li><a href="#">회사소개</a></li>
                <li><a href="#">이용약관</a></li>
                <li><a href="#">이용안내</a></li>
                <li><a href="#">개인정보취급방침</a></li>
            </ul>
        </article>
        <article class="xe-shop-guide">
            <h3 class="xe-sr-only">회사 정보</h3>
            <dl>
                <dt>CS CENTER</dt>
                <dd class="xe-shop-guide-bold"><i class="xi-call"></i>{{$shopConfig->get('csPhone')}}</dd>
                <dd>{!! $shopConfig->get('csRunTime') !!}</dd>
                {{--<dd>평일  오전 10:00 ~ 오후 06:00</dd>--}}
                {{--<dd>점심 오후 12:00 ~ 오후 01:00</dd>--}}
                {{--<dd>휴무 토/일/공휴일</dd>--}}
                <dd>{{$shopConfig->get('csEmail')}}</dd>
            </dl>
            <dl>
                <dt>BANK</dt>
                <dd>국민은행  12345-678-123123</dd>
                <dd>국민은행  12345-678-123123</dd>
                <dd>국민은행  12345-678-123123</dd>
                <dd>예금주  홍길동</dd>
            </dl>
            <dl>
                <dt>DELIVERY</dt>
                <dd>반품주소  (12345) 서울특별시 강남구 00길 000빌딩 000호 DAZE</dd>
                <dd>반품요청  우체국 택배  1588-1300</dd>
            </dl>
        </article>
    </div>
</section>
<footer class="xe-shop footer">
    <section class="xe-company-info">
        <div class="container">
            <dl>
                <dt>상호명</dt>
                <dd>{{$shopConfig->get('corpName')}}</dd>
                <dt>대표자</dt>
                <dd>{{$shopConfig->get('repName')}}</dd>
                <dt>사업자등록번호</dt>
                <dd>{{$shopConfig->get('corpNo')}}<a href="http://www.ftc.go.kr/info/bizinfo/communicationList.jsp" target="_blank">[사업자정보확인]</a></dd>
                <dt>통신판매업신고</dt>
                <dd>{{$shopConfig->get('mailOrderBusinessNo')}}</dd>
            </dl>
            <dl>
                <dt>주소</dt>
                <dd>{{$shopConfig->get('corpAddress')}}</dd>
                <dt>대표전화</dt>
                <dd>{{$shopConfig->get('repPhone')}}</dd>
            </dl>
            <dl>
                <dt>개인정보관리책임자</dt>
                <dd>{{$shopConfig->get('cpoName')}}</dd>
                <dt>이메일</dt>
                <dd>{{$shopConfig->get('cpoEmail')}}</dd>
            </dl>
        </div>
    </section>
    <section class="copyright">
        <div class="container">
            <p>Copyright &copy; 2016 쇼핑몰 이름 All rights reserved.&nbsp;&nbsp;&nbsp;MADE BY XE</p>
        </div>
    </section>
</footer>
