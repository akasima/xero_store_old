<?php
namespace Akasima\RichShop\Controllers;

use Akasima\RichShop\Models\Option;
use Akasima\RichShop\Models\Product;
use Akasima\RichShop\Models\ProductCategory;
use Akasima\RichShop\Models\Seller;
use Akasima\RichShop\Plugin;
use Akasima\RichShop\Modules\Shop as ShopModule;
use App\Http\Controllers\Controller;
use Xpressengine\Database\Eloquent\Builder;
use Illuminate\Http\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use XePresenter;
use XeConfig;
use XeCategory;
use XeEditor;
use Xpressengine\Category\Models\Category;
use Xpressengine\Category\Models\CategoryItem;
use Xpressengine\Editor\EditorHandler;
use Xpressengine\Http\Request;
use Auth;
use Gate;
use Xpressengine\Media\MediaManager;
use Xpressengine\Permission\Instance;
use Xpressengine\Storage\Storage;
use Xpressengine\Support\Exceptions\AccessDeniedHttpException;
use Xpressengine\Support\Exceptions\InvalidArgumentException;


class SettingsController extends Controller
{
    public function __construct()
    {
        XePresenter::setSettingsSkinTargetId(Plugin::getId());
    }

    public function shopInfo()
    {
        $config = XeConfig::get(Plugin::getId());

        return XePresenter::make('configure.shopInfo', [
            'config' => $config,
        ]);
    }

    public function storeShopInfo(Request $request)
    {
        $config = XeConfig::get(Plugin::getId());
        $config->set('corpNo', $request->input('corpNo'));
        $config->set('corpName', $request->input('corpName'));
        $config->set('corpAddress', $request->input('corpAddress'));
        $config->set('repName', $request->input('repName'));
        $config->set('repPhone', $request->input('repPhone'));
        $config->set('repEmail', $request->input('repEmail'));
        $config->set('mailOrderBusinessNo', $request->input('mailOrderBusinessNo'));

        $config->set('csPhone', $request->input('csPhone'));
        $config->set('csEmail', $request->input('csEmail'));
        $config->set('csRunTime', $request->input('csRunTime'));

        $config->set('cpoName', $request->input('cpoName'));
        $config->set('cpoPhone', $request->input('cpoPhone'));
        $config->set('cpoEmail', $request->input('cpoEmail'));

        XeConfig::modify($config);

        $seller = Seller::find(Auth::user()->getId());
        if ($seller === null) {
            $seller = new Seller();
        }
        $seller->user_id = Auth::user()->getId();
        $seller->corp_no = $config->get('corpNo');
        $seller->corp_name = $config->get('corpName');
        $seller->corp_address = $config->get('corpAddress');
        $seller->rep_name = $config->get('repName');
        $seller->rep_phone = $config->get('repPhone');
        $seller->rep_email = $config->get('repEmail');
        $seller->mail_order_business_no = $config->get('mailOrderBusinessNo');
        $seller->save();

        return redirect(route('rich_shop.settings.configure.shopInfo'));
    }

    public function category()
    {
        $config = XeConfig::get(Plugin::getId());

        $category = Category::find($config->get('categoryId'));

        if ($category === null) {
            throw new \Exception;
        }

        // 카테고리 관리자 view 사용
        XePresenter::setSettingsSkinTargetId('');
        return XePresenter::make('category.show', compact('category'));
    }

//    public function storeCategory(Request $request)
//    {
//        $category = XeCategory::create([
//            'name' => $request->input('name'),
//        ]);
//
//        $config = XeConfig::get(Plugin::getId());
//
//        $categories = $config->get('categories');
//        array_push($categories, $category->id);
//        $config->set('categories', $categories);
//        XeConfig::modify($config);
//
//        return redirect(route('rich_shop.settings.configure.category'));
//    }

    /**
     * 상품 등록
     */
    public function createProduct()
    {
        /** @var \Akasima\RichShop\ProductHandler $productHandler */
        $productHandler = app('xe.rich.shop.product');

        $config = XeConfig::get(Plugin::getId());
        $categoryItems = Category::find($config->get('categoryId'))->getProgenitors();

        // 스킨이 요구하는 썸네일 사이즈
        $productHandler->addThumbnailDimensionsBySkin(app('xe.skin'));

        return XePresenter::make('product.create', [
            'config' => $config,
            'categoryItems' => $categoryItems,
        ]);
    }

    public function storeProduct(Request $request)
    {
        /** @var \Akasima\RichShop\ProductHandler $productHandler */
        $productHandler = app('xe.rich.shop.product');

        $inputs = $request->all();
        $inputs['description'] = purify($request->originAll()['description']);

        $inputs['product_code'] = $inputs['product_manage_code'];

        /** @var \Xpressengine\Editor\AbstractEditor $editor */
        $editor = XeEditor::get(Plugin::getId());

        // set file, tag
        $inputs['_files'] = array_get($inputs, $editor->getFileInputName(), []);
        $inputs['_hashTags'] = array_get($inputs, $editor->getTagInputName(), []);

        $seller = Seller::find(Auth::user()->getId());

        $product = $productHandler->add($inputs, $seller);

        return redirect(route('rich_shop.settings.product.edit', ['id' => $product->id]));
    }

    /**
     * 상품 이미지 등록
     *
     * @param Request      $request
     * @param Storage      $storage
     * @param MediaManager $mediaManager
     * @return
     */
    public function uploadProductImage(Request $request, Storage $storage, MediaManager $mediaManager)
    {
        $uploadedFile = null;
        if ($request->file('image') !== null) {
            $uploadedFile = $request->file('image');
        }

        if ($uploadedFile === null) {
            throw new InvalidArgumentException;
        }

        /** @var \Akasima\RichShop\ProductHandler $productHandler */
        $productHandler = app('xe.rich.shop.product');

        // 스킨이 요구하는 썸네일 사이트
        $productHandler->addThumbnailDimensionsBySkin(app('xe.skin'));

        $file = $productHandler->uploadImage($uploadedFile, $storage);

        list($media, $thumbnails) = $productHandler->createThumbnails($file, $mediaManager);

        return XePresenter::makeApi([
            'file' => $file->toArray(),
            'media' => $media,
            'thumbnails' => $thumbnails,
        ]);
    }

    /**
     * 상품 이미지 등록
     *
     * @param Request      $request
     * @param MediaManager $mediaManager
     * @param string       $code          thumbnail code
     * @return
     */
    public function uploadProductThumbnail(Request $request, MediaManager $mediaManager, $code)
    {
        $uploadedFile = null;
        if ($request->file('image') !== null) {
            $uploadedFile = $request->file('image');
        }

        if ($uploadedFile === null) {
            throw new InvalidArgumentException;
        }

        /** @var \Akasima\RichShop\ProductHandler $productHandler */
        $productHandler = app('xe.rich.shop.product');

        // 스킨이 요구하는 썸네일 사이트
        $thumbnail = $productHandler->updateThumbnail($uploadedFile, $mediaManager, $request->input('id'), $code);

        return XePresenter::makeApi([
            'thumbnail' => $thumbnail,
        ]);
    }

    public function productList(Request $request)
    {
        /** @var \Akasima\RichShop\ProductHandler $productHandler */
        $productHandler = app('xe.rich.shop.product');

        $config = XeConfig::get(Plugin::getId());
        $categoryItems = Category::find($config->get('categoryId'))->getProgenitors();

        $selectedCategoryItem = CategoryItem::find($request->get('categoryItemId'));
        if ($selectedCategoryItem === null) {
            $selectedCategoryItem = new CategoryItem();
        }

        $periods = [
            ['value' => '1,week', 'text' => 'board::1week'],
            ['value' => '2,week', 'text' => 'board::2week'],
            ['value' => '1,month', 'text' => 'board::1month'],
            ['value' => '3,month', 'text' => 'board::3month'],
            ['value' => '6,month', 'text' => 'board::6month'],
            ['value' => '1,year', 'text' => 'board::1year'],
        ];

        // 스킨이 요구하는 썸네일 사이즈
        $productHandler->addThumbnailDimensionsBySkin(app('xe.skin'));

        /** @var Builder $query */
        $model = new Product();
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
        $productHandler->filterResolver($query, $request);

        $query->groupBy('id');
        $paginate = $query->paginate(30)->appends($request->except('page'));

        return XePresenter::make('product.list', [
            'config' => $config,
            'categoryItems' => $categoryItems,
            'selectedCategoryItem' => $selectedCategoryItem,
            'periods' => $periods,
            'paginate' => $paginate,
        ]);
    }

    public function editItemsModule($id)
    {

    }

    public function editProduct($id)
    {
        /** @var Product $product */
        $product = Product::find($id);
        if ($product === null) {
            throw new \Exception;
        }

        /** @var \Akasima\RichShop\ProductHandler $productHandler */
        $productHandler = app('xe.rich.shop.product');

        $config = XeConfig::get(Plugin::getId());
        $categoryItems = Category::find($config->get('categoryId'))->getProgenitors();

        $productCategories = ProductCategory::where('product_id', $product->id)->get();

        // 스킨이 요구하는 썸네일 사이즈
        $productHandler->addThumbnailDimensionsBySkin(app('xe.skin'));

        return XePresenter::make('product.edit', [
            'config' => $config,
            'productCategories' => $productCategories,
            'product' => $product,
            'productDetailImageIds' => explode(',', $product->product_detail_image_order),
            'categoryItems' => $categoryItems,
        ]);
    }

    public function updateProduct(Request $request, $id)
    {
        // 셀러 체크
        // 최고 관리자, 쇼핑몰 관리자 아니면 다른 셀러의 상품을 수정할 수 없음
        $product = Product::find($id);
        if ($product === null) {
            throw new \Exception;
        }

        /** @var \Akasima\RichShop\ProductHandler $productHandler */
        $productHandler = app('xe.rich.shop.product');

        $inputs = $request->all();
        $inputs['description'] = purify($request->originAll()['description']);

        /** @var \Xpressengine\Editor\AbstractEditor $editor */
        $editor = XeEditor::get(Plugin::getId());

        // set file, tag
        $inputs['_files'] = array_get($inputs, $editor->getFileInputName(), []);
        $inputs['_hashTags'] = array_get($inputs, $editor->getTagInputName(), []);

        $productHandler->put($product, $inputs);

        return redirect(route('rich_shop.settings.product.edit', ['id'=> $product->id]));
    }

    public function storeOption(Request $request, $productId)
    {
        $product = Product::find($productId);
        if ($product === null) {
            throw new \Exception;
        }

        $optionHandler = app('xe.rich.shop.option');

        $inputs = $request->all();
        $option = $optionHandler->add($inputs);

        return redirect(route('rich_shop.settings.product.edit', ['id'=> $option->product_id]));
    }

    public function updateOption(Request $request, $productId, $id)
    {
        $option = Option::find($id);
        if ($option === null) {
            throw new \Exception;
        }

        $optionHandler = app('xe.rich.shop.option');

        $inputs = $request->all();
        $optionHandler->put($option, $inputs);

        return redirect(route('rich_shop.settings.product.edit', ['id'=> $option->product_id]));
    }

    /**
     * 전체 주문 내역
     *
     * @return mixed
     */
    public function order()
    {
        $config = XeConfig::get(Plugin::getId());

        $orders = [];

        return XePresenter::make('order.index', [
            'config' => $config,
            'orders' => $orders,
        ]);
    }

    public function editShopModule($instanceId)
    {
        $config = XeConfig::get(Plugin::getId());
        $categoryItems = Category::find($config->get('categoryId'))->getProgenitors();

        $itemsConfig = XeConfig::get(sprintf('%s.%s', ShopModule::getId(), $instanceId));

        $selectedCategoryItem = CategoryItem::find($itemsConfig->get('categoryItemId'));
        if ($selectedCategoryItem === null) {
            $selectedCategoryItem = new CategoryItem();
        }

        return XePresenter::make('shopModule.edit', [
            'instanceId' => $instanceId,
            'itemsConfig' => $itemsConfig,
            'categoryItems' => $categoryItems,
            'selectedCategoryItem' => $selectedCategoryItem,
        ]);
    }

    public function updateShopModule(Request $request, $instanceId)
    {
        $itemsConfig = XeConfig::get(sprintf('%s.%s', ShopModule::getId(), $instanceId));

        $itemsConfig->set('categoryItemId', $request->input('categoryItemId'));
        $itemsConfig->set('categoryItemDepth', $request->input('categoryItemDepth'));
        XeConfig::modify($itemsConfig);

        return redirect(route('rich_shop.settings.items.edit', ['id'=> $instanceId]));
    }

    /**
     * 위젯 사진 업로드
     */
    public function widgetImageUpload(Request $request, Storage $storage, MediaManager $mediaManager, $size)
    {
        $uploadedFile = null;
        if ($request->file('image') !== null) {
            $uploadedFile = $request->file('image');
        }

        if ($uploadedFile === null) {
            throw new InvalidArgumentException;
        }

        /** @var \Akasima\RichShop\ProductHandler $productHandler */
        $productHandler = app('xe.rich.shop.product');

        // 스킨이 요구하는 썸네일 사이트
        $productHandler->addThumbnailDimensionsBySkin(app('xe.skin'));

        $file = $productHandler->uploadImage($uploadedFile, $storage);

        list ($width, $height) = explode('x', $size);
        $media = $mediaManager->make($file);
        $thumbnails = $mediaManager->createThumbnails($media, 'fit', [
            $size => ['width' => $width, 'height' => $height],
        ]);

        return XePresenter::makeApi([
            'file' => $file->toArray(),
            'media' => $media,
            'thumbnails' => $thumbnails,
        ]);
    }
}
