<?php
namespace Akasima\RichShop\Models;

use Xpressengine\Category\Models\CategoryItem;
use Xpressengine\Database\Eloquent\DynamicModel;

class ProductCategory extends DynamicModel
{
    public $table = 'rich_shop_product_categories';

    protected $primaryKey = 'product_category_id';

    public $timestamps = false;

    protected $fillable = [
        'product_id', 'category_id',
    ];

    public function categoryItem()
    {
        return $this->belongsTo(CategoryItem::class, 'selected_item_id', 'id');
    }

    /**
     * get breadcrumbs
     *
     * @return CategoryItem[]
     */
    public function getBreadcrumbs()
    {
        $breadcrumbs = [];
        $items = $this->categoryItem->getBreadcrumbs();

        foreach ($items as $categoryItem) {
            $breadcrumbs[] = $categoryItem;
        }

        return $breadcrumbs;
    }
}
