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
        </div>
    @endforeach
</div>

<script>
    $(function() {
        $('.product-add').click(function () {
            var $item = $('<div class="item">');

            $item.html('<button type="button" class="xe-btn xe-btn-danger product-remove"> 상품 삭제</button>' +
                    '<label>상품 아이디</label>' +
                    '<input type="text" name="product_id[]" class="form-control" />');

            $('.products').append($item);
        });

        $('.products').on('click', '.product-remove', function (event) {
            var $btn = $(this),
                    $item = $btn.closest('.item');
            $item.remove();
        });
    });
</script>