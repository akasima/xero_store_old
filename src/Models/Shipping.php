<?php
namespace Akasima\RichShop\Models;

use Xpressengine\Database\Eloquent\DynamicModel;

class Shipping extends DynamicModel
{
    public $table = 'rich_shop_shippings';

    public $incrementing = false;

    public $timestamps = false;

    const RECENT_TYPE = -1;
}
