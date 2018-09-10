<div class="form-group">
    <label>타이틀</label>
    <input type="text" name="title" class="form-control" value="{{$args['title']}}" />
</div>

<button type="button" class="xe-btn xe-btn-primary product-add"> 상품 추가</button>
<div class="products">
    @foreach ($items as $item)
        <div class="item">
            <button type="button" class="xe-btn xe-btn-danger product-remove"> 상품 삭제</button>
            <label>상품 아이디</label>
            <input type="text" name="product_id[]" class="form-control" value="{{$item['product_id']}}" />
            <input type="text" name="product_title[]" class="form-control" value="{{$item['product_title']}}" />
            <input type="text" name="product_comment[]" class="form-control" value="{{$item['product_comment']}}" />

            <div class="rich-shop-widget-thumb" data-url="{{route('rich_shop.settings.widget.image.upload', ['size' => '600x375'])}}">
                <input type="text" name="product_file_id[]" class="file-id" value="{{$item['product_file_id']}}" />
                상품 썸네일 등록(600 * 375px) <input type="file" class="product-image" >
                <div class="img">
                    @if ($item['product_image'] != null) {!! $item['product_image']->render() !!} @endif
                </div>
            </div>
        </div>
    @endforeach
</div>

<script src="{{url()}}/assets/vendor/jQuery-File-Upload/js/vendor/jquery.ui.widget.js"></script>
<script src="{{url()}}/assets/vendor/jQuery-File-Upload/js/jquery.iframe-transport.js"></script>
<script src="{{url()}}/assets/vendor/jQuery-File-Upload/js/jquery.fileupload.js"></script>
<script src="{{url()}}/plugins/rich_shop/assets/js/widgetSettings.js"></script>

<script>
    $(function() {
        Widget.SettingImage.init();

        $('.product-add').click(function () {
            var $item = $('<div class="item">');

            $item.html('<button type="button" class="xe-btn xe-btn-danger product-remove"> 상품 삭제</button>' +
                    '<label>상품 아이디</label>' +
            '<input type="text" name="product_id[]" class="form-control" />' +
                    '<input type="text" name="product_title[]" class="form-control" />' +
                    '<input type="text" name="product_comment[]" class="form-control" />' +
                    '<div class="rich-shop-widget-thumb" data-url="{{route('rich_shop.settings.widget.image.upload', ['size' => '600x375'])}}">' +
                    '<input type="text" name="product_file_id[]" class="file-id" value="" />' +
                    '상품 썸네일 등록(600 * 375px) <input type="file" class="product-image" >' +
                    '<div class="img"> </div>' +
                    '</div>');

            $('.products').append($item);

            var $box = $item.find('.rich-shop-widget-thumb'),
            options = Widget.SettingImage.uploadOptions($box);

            $box.fileupload($.extend(options, {
                done: function (e, data) {
                    console.log(data);
                    var thumbnails = data.result.thumbnails,
                            file = data.result.file,
                            img = $('<img>');


                    // 이전 이미지 제거
                    $box.find('.img img').remove();

                    img.attr('src', thumbnails[0].url);
                    $box.find('.img').append(img);
                    $box.find('.file-id').val(file.id);
                }
            }));
        });

        $('.products').on('click', '.product-remove', function (event) {
            var $btn = $(this),
                    $item = $btn.closest('.item');
            $item.remove();
        })
    });
</script>