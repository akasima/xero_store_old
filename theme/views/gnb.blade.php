<h2 class="xe-sr-only">메뉴</h2>
<ul class="xe-shop-menu">
    @foreach(menu_list($config->get('gnb')) as $menu)
    <li @if($menu['selected']) class="active" @endif>
        <a href="{{ url($menu['url']) }}">{{ $menu['link'] }}</a>
        @if(count($menu['children']))
            <ul>
                @foreach($menu['children'] as $menu1)
                    <li @if($menu1['selected']) class="on" @endif>
                        <a href="{{ url($menu1['url']) }}">{{ $menu1['link'] }}</a>
                    </li>
                @endforeach
            </ul>
        @endif
    </li>
    @endforeach
</ul>

<h2 class="xe-sr-only">카테고리</h2>
<button type="button" class="xe-btn-category"><i class="xi-bars"></i><span class="xe-sr-only">전체 카테고리 열기</span></button>
<!-- [D] 활성화 시 active 클래스 추가 부탁드립니다-->
<ul class="xe-shop-category">
    <li>
        <h3 class="xe-shop-category-title">category 1</h3>
        <ul>
            <li><a href="#">2depth</a></li>
            <li><a href="#">2depth2depth2depth2depth</a></li>
            <li><a href="#">2depth</a></li>
            <li><a href="#">2depth</a></li>
            <li><a href="#">2depth</a></li>
            <li><a href="#">2depth</a></li>
            <li><a href="#">2depth</a></li>
            <li><a href="#">2depth</a></li>
        </ul>
    </li>
    <li>
        <h3 class="xe-shop-category-title">category 2</h3>
        <ul>
            <li><a href="#">2depth</a></li>
        </ul>
    </li>
    <li>
        <h3 class="xe-shop-category-title">category 3</h3>
        <ul>
            <li><a href="#">2depth</a></li>
        </ul>
    </li>
    <li>
        <h3 class="xe-shop-category-title">category 4</h3>
        <ul>
            <li><a href="#">2depth</a></li>
        </ul>
    </li>
    <li>
        <h3 class="xe-shop-category-title">category 5</h3>
        <ul>
            <li><a href="#">2depth</a></li>
        </ul>
    </li>
    <li>
        <h3 class="xe-shop-category-title">category 6</h3>
        <ul>
            <li><a href="#">2depth</a></li>
        </ul>
    </li>
    <li>
        <h3 class="xe-shop-category-title">category 7</h3>
        <ul>
            <li><a href="#">2depth</a></li>
            <li><a href="#">2depth</a></li>
            <li><a href="#">2depth</a></li>
            <li><a href="#">2depth</a></li>
            <li><a href="#">2depth</a></li>
            <li><a href="#">2depth</a></li>
            <li><a href="#">2depth</a></li>
        </ul>
    </li>
    <li>
        <h3 class="xe-shop-category-title">category 8</h3>
        <ul>
            <li><a href="#">2depth</a></li>
            <li><a href="#">2depth</a></li>
            <li><a href="#">2depth</a></li>
            <li><a href="#">2depth</a></li>
            <li><a href="#">2depth</a></li>
            <li><a href="#">2depth</a></li>
            <li><a href="#">2depth</a></li>
        </ul>
    </li>
    <li>
        <h3 class="xe-shop-category-title">category 9</h3>
        <ul>
            <li><a href="#">2depth</a></li>
            <li><a href="#">2depth</a></li>
            <li><a href="#">2depth</a></li>
            <li><a href="#">2depth</a></li>
            <li><a href="#">2depth</a></li>
            <li><a href="#">2depth</a></li>
            <li><a href="#">2depth</a></li>
        </ul>
    </li>
</ul>