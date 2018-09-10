<?php
namespace Akasima\RichShop\Models;

use Xpressengine\Database\Eloquent\DynamicModel;

class OrderStatusLog extends DynamicModel
{
    public $table = 'rich_shop_order_status_logs';

    public $timestamps = false;
}
