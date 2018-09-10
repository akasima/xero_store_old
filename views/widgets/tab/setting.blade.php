<div class="form-group">
    <label>타이틀</label>
    <input type="text" name="title" class="form-control" value="{{$args['title']}}" />
</div>

<button type="button" class="xe-btn xe-btn-primary tab-add"> 탭 추가</button>
<div class="tabs">
    @foreach ($tabItems as $tabItem)
        <div class="tab">
            <button type="button" class="xe-btn xe-btn-danger tab-remove">탭 삭제</button>
            <div class="form-group">
                <label>탭 이름</label>
                <input type="text" name="tab_name[]" class="form-control" value="{{$tabItem['tab_name']}}" />
            </div>
            @for ($i=0; $i<3; $i++)
                <div class="product">
                    <label>상품 아이디</label>
                    <input type="text" name="product{{$i}}_id[]" class="form-control" value="{{$tabItem['product'.$i.'_id']}}" />
                    <div class="rich-shop-widget-thumb" data-url="{{route('rich_shop.settings.widget.image.upload', ['size' => '369x240'])}}">
                        <input type="text" name="product{{$i}}_file_id[]" class="file-id" value="{{$tabItem['product'.$i.'_file_id']}}" />
                        상품 썸네일 등록(369 * 240px) <input type="file" class="product-image" >
                        <div class="img">
                            @if ($tabItem['product'.$i.'_image'] != null) {!! $tabItem['product'.$i.'_image']->render() !!} @endif
                        </div>
                    </div>
                </div>
            @endfor
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

        $('.tab-add').click(function () {
            var $item = $('<div class="tab">'),
                    html = '',
                    route = '{{route('rich_shop.settings.widget.image.upload', ['size' => '369x240'])}}';

            html += '<div class="tab">' +
                    '<button type="button" class="xe-btn xe-btn-danger tab-remove">탭 삭제</button>' +
                    '<div class="form-group">' +
                    '<label>탭 이름</label>' +
                    '<input type="text" name="tab_name[]" class="form-control" />' +
                    '</div>';
            for (var i =0; i<3; i++) {
                html += '<div class="product">' +
                        '<label>상품 아이디</label>' +
                        '<input type="text" name="product' + i + '_id[]" class="form-control" />' +
                        '<div class="rich-shop-widget-thumb" data-url="' + route + '">' +
                        '<input type="text" name="product' + i + '_file_id[]" class="file-id" />' +
                        '상품 썸네일 등록(369 * 240px) <input type="file" class="product-image" >' +
                        '<div class="img"> </div>' +
                        '</div>'+
                        '</div>';
            }
            html += '</div>';

            $item.html(html);

            $('.tabs').append($item);

            var $boxs = $item.find('.rich-shop-widget-thumb');
            $boxs.each(function () {
                var $box = $(this),
                        options = Widget.SettingImage.uploadOptions($box)

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
        });

        $('.tabs').on('click', '.tab-remove', function (event) {
            var $btn = $(this),
                    $item = $btn.closest('.tab');
            $item.remove();
        })
    });
</script>