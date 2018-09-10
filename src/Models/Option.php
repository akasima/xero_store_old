<?php
namespace Akasima\RichShop\Models;

use Xpressengine\Database\Eloquent\DynamicModel;

class Option extends DynamicModel
{
    public $table = 'rich_shop_options';

    public $incrementing = false;

    protected $fillable = [
        'option_name', 'product_id', 'parent_id', 'stock_quantity', 'additional_price',
    ];

    protected $casts = [
        'stockQuantity' => 'int',
        'additionalPrice' => 'int',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    /**
     * get is sellable
     *
     * @return bool
     */
    public function sellable()
    {
        // check stockQuantity & ordered quantity

        $product = $this->product;
        if ($product === null) {
            return false;
        }
        if ($product->sale !== Product::SALE_CLOSE) {
            return false;
        }

        return true;
    }
}
