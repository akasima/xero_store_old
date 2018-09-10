<?php
namespace Akasima\RichShop\Models;

use Xpressengine\Database\Eloquent\DynamicModel;

class Cart extends DynamicModel
{
    public $table = 'rich_shop_carts';

    public $incrementing = false;

    public function cartOptions()
    {
        return $this->hasMany(CartOption::class, 'cart_id');
    }

    public function product()
    {
        return $this->hasOne(Product::class, 'id', 'product_id');
    }
}
