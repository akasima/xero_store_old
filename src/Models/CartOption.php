<?php
namespace Akasima\RichShop\Models;

use Xpressengine\Database\Eloquent\DynamicModel;

class CartOption extends DynamicModel
{
    public $table = 'rich_shop_cart_options';

    public $incrementing = false;

    public $timestamps = false;

    public function option()
    {
        return $this->hasOne(Option::class, 'id', 'option_id');
    }

    public function product()
    {
        return $this->hasOne(Product::class, 'id', 'product_id');
    }

    public function sellable()
    {
        /** @var Option $option */
        $option = $this->option;
        if ($option === null) {
            return false;
        }
        return $option->sellable();
    }

    public function cart()
    {
        return $this->belongsTo(Cart::class, 'cart_id', 'id');
    }
}
