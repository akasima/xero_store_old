<?php
namespace Akasima\RichShop\Controllers;

use Akasima\RichShop\Plugin;
use Akasima\RichShop\QnaCommentUsable;
use Xpressengine\Config\ConfigEntity;
use Akasima\RichShop\Models\Product;
use Akasima\RichShop\Models\ProductCategory;
use Akasima\RichShop\Models\Slug;
use Xpressengine\Category\Models\CategoryItem;

class ShopService
{
    public function __construct()
    {
        \XeTheme::selectTheme('theme/xero_store@theme');
        \XePresenter::setSkinTargetId(Plugin::getId());
    }

    public function getItems(ConfigEntity $config = null)
    {
        /** @var \Xpressengine\Http\Request $request */
        $request = app('request');

        /** @var \Akasima\RichShop\ProductHandler $productHandler */
        $productHandler = app('xe.rich.shop.product');

        $categoryItem = CategoryItem::find($config->get('categoryItemId'));

        $model = new Product();
        $model->visible();
        $query = $model->newQuery();

        // join category table
        $categoryModel = new ProductCategory();
        $categoryQuery = $categoryModel->newQuery();

        $query->leftJoin(
            $categoryQuery->getQuery()->from,
            sprintf('%s.%s', $query->getQuery()->from, $model->getKeyName()),
            '=',
            sprintf('%s.%s', $categoryQuery->getQuery()->from, 'product_id')
        );

        $request->query->set('withLowerCategoryItem', '1');
        if ($request->has('categoryItemDepth') === false) {
            $request->query->set('categoryItemDepth', $config->get('categoryItemDepth'));
        }
        if ($request->has('categoryItemId') === false) {
            $request->query->set('categoryItemId', $config->get('categoryItemId'));
        }

        $productHandler->filterResolver($query, $request);

        $query = $query->where('display', '<>', Product::DISPLAY_HIDDEN);

        $query->groupBy('id');
        $paginate = $query->paginate(30)->appends($request->except('page'));

        return [
            'categoryItem' => $categoryItem,
            'categoryChildren' => $categoryItem->getChildren(),
            'paginate' => $paginate,
        ];
    }

    public function getProduct($slug, ConfigEntity $config = null)
    {
        /** @var \Xpressengine\Http\Request $request */
        $request = app('request');

        $product = Slug::where('slug', $slug)->first()->product;

        /** @var CategoryItem $categoryItem */
        $categoryItem = null;
        if ($request->has('categoryItemId') === false && $config !== null) {
            $categoryItem = CategoryItem::find($config->get('categoryItemId'));
        } else if ($request->has('categoryItemId') === true) {
            $categoryItem = CategoryItem::find($request->get('categoryItemId'));
        }

        if ($categoryItem === null) {
            throw new \Exception('category item id not founded');
        }

        $breadcrumbs = [];
        $ids = $categoryItem->getBreadcrumbs();

        foreach ($ids as $id) {
            $breadcrumbs[] = CategoryItem::find($id->id);
        }

        $productDetailImageIds = [];
        if ($product->product_detail_image_order != null) {
            $productDetailImageIds = explode(',', $product->product_detail_image_order);
        }
        return [
            'categoryItem' => $categoryItem,
            'breadcrumbs' => $breadcrumbs,
            'product' => $product,
            'qnaCommentUsable' => new QnaCommentUsable($product),
            'productDetailImageIds' => $productDetailImageIds,
        ];
    }
}
