<div class="form-group">
    <label>타이틀</label>
    <input type="text" name="title" class="form-control" value="{{$args['title']}}" />
</div>

<div>
    <label>상품1 (위) 상품 아이디</label>
    <input type="text" name="product1_id" class="form-control" value="{{$args['product1_id']}}" />

    <div class="rich-shop-widget-thumb" data-url="{{route('rich_shop.settings.widget.image.upload', ['size' => '367x238', 'related' => 'product1'])}}" data-related="product1" data-size="367x238">
        <input type="text" name="product1_file_id" class="file-id" value="{{$args['product1_file_id']}}" />
        상품 썸네일 등록(367 * 238px) <input type="file" class="product-image" >
        <div class="img">
            @if ($product1Image != null) {!! $product1Image->render() !!} @endif
        </div>
    </div>
</div>

<div>
    <label>상품2 (우측) 상품 아이디</label>
    <input type="text" name="product2_id" class="form-control" value="{{$args['product2_id']}}" />

    <div class="rich-shop-widget-thumb" data-url="{{route('rich_shop.settings.widget.image.upload', ['size' => '367x490', 'related' => 'product2'])}}" data-related="product2" data-size="367x490">
        <input type="text" name="product2_file_id" class="file-id" value="{{$args['product2_file_id']}}" />
        상품 썸네일 등록(367 * 490px) <input type="file" class="product-image" >
        <div class="img">
            @if ($product2Image != null) {!! $product2Image->render() !!} @endif
        </div>
    </div>
</div>

<div>
    <label>상품3 (아래) 상품 아이디</label>
    <input type="text" name="product3_id" class="form-control" value="{{$args['product3_id']}}" />

    <div class="rich-shop-widget-thumb" data-url="{{route('rich_shop.settings.widget.image.upload', ['size' => '367x238', 'related' => 'product3'])}}" data-related="product3" data-size="367x238">
        <input type="text" name="product3_file_id" class="file-id" value="{{$args['product3_file_id']}}" />
        상품 썸네일 등록(367 * 238px) <input type="file" class="product-image" >
        <div class="img">
            @if ($product3Image != null) {!! $product3Image->render() !!} @endif
        </div>
    </div>
</div>

<div>
    <label>상품4 (좌측) 상품 아이디</label>
    <input type="text" name="product4_id" class="form-control" value="{{$args['product4_id']}}" />

    <div class="rich-shop-widget-thumb" data-url="{{route('rich_shop.settings.widget.image.upload', ['size' => '367x490', 'related' => 'product4'])}}" data-related="product4" data-size="367x490">
        <input type="text" name="product4_file_id" class="file-id" value="{{$args['product4_file_id']}}" />
        상품 썸네일 등록(367 * 490px) <input type="file" class="product-image" >
        <div class="img">
            @if ($product4Image != null) {!! $product4Image->render() !!} @endif
        </div>
    </div>
</div>

<script src="{{url()}}/assets/vendor/jQuery-File-Upload/js/vendor/jquery.ui.widget.js"></script>
<script src="{{url()}}/assets/vendor/jQuery-File-Upload/js/jquery.iframe-transport.js"></script>
<script src="{{url()}}/assets/vendor/jQuery-File-Upload/js/jquery.fileupload.js"></script>
<script src="{{url()}}/plugins/rich_shop/assets/js/widgetSettings.js"></script>

<script>
    $(function() {
        Widget.SettingImage.init();
    });
</script>