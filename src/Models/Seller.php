<?php
namespace Akasima\RichShop\Models;

use Xpressengine\Database\Eloquent\DynamicModel;

class Seller extends DynamicModel
{
    public $table = 'rich_shop_sellers';
    protected $primaryKey = 'user_id';
}
