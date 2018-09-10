<?php
namespace Akasima\RichShop;

use Akasima\RichShop\Models\ProductCategory;
use Akasima\RichShop\Models\Product;
use Akasima\RichShop\Models\Seller;
use Akasima\RichShop\Models\Slug;
use Akasima\RichShop\Skins\ShopSkin;
use Gate;
use Illuminate\Database\Eloquent\Builder;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Xpressengine\Category\Models\CategoryItem;
use Xpressengine\Editor\EditorHandler;
use Xpressengine\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Xpressengine\Media\CommandFactory;
use Xpressengine\Media\Coordinators\Dimension;
use Xpressengine\Media\Handlers\ImageHandler;
use Xpressengine\Media\MediaManager;
use Xpressengine\Media\Models\Image;
use Xpressengine\Media\Models\Media;
use Xpressengine\Permission\Instance;
use Xpressengine\Skin\SkinHandler;
use Xpressengine\Storage\Storage;
use Xpressengine\Support\Exceptions\AccessDeniedHttpException;
use Xpressengine\Support\Exceptions\InvalidArgumentException;

/**
 * Product
 *
 * 상품 관리에 필요한 요소
 *
 * @package Akasima\RichShop
 */
class ProductHandler
{
    const FILE_UPLOAD_PATH = 'public/rich_shop';

    protected $thumbnailType = 'fit';
    protected $thumbnailDimensions = [
        '500x500' => ['width' => 500, 'height' => 500],
        '200x200' => ['width' => 200, 'height' => 200],
    ];

    /**
     * @var Storage
     */
    protected $storage;

    /**
     * @var OptionHandler
     */
    protected $optionHandler;

    public function __construct(Storage $storage, OptionHandler $optionHandler)
    {
        $this->storage = $storage;
        $this->optionHandler = $optionHandler;
    }

    /**
     * add thumbnail size by skin
     *
     * @param SkinHandler $skinHandler
     * @return void
     */
    public function addThumbnailDimensionsBySkin(SkinHandler $skinHandler)
    {
        //$skin = $skinHandler->getAssigned(Plugin::getId());
        /** @var \Akasima\RichShop\Skins\AbstractShopSkin $skin */
        $skin = $skinHandler->get(ShopSkin::getId());

        foreach ($skin->getThumbnailCodes() as $code) {
            list ($width, $height) = explode('x', $code);
            $this->addThumbnailDimension($width, $height);
        }
    }

    /**
     * add thumbnail size
     *
     * @param int $width
     * @param int $height
     * @return void
     */
    public function addThumbnailDimension($width, $height)
    {
        $key = sprintf('%sx%s', $width, $height);
        $this->thumbnailDimensions[$key] = [
            'width' => $width,
            'height' => $height,
        ];
    }

    /**
     * get thumbnail dimensions
     *
     * @return array
     */
    public function getThumbnailDimensions()
    {
        return $this->thumbnailDimensions;
    }

    /**
     * get thumbnail image
     *
     * @param string $id   product id
     * @param string $code thumbnail code
     * @return null|Image
     */
    public function getThumbnail($id, $code)
    {
        /** @var Media $media */
        $media = Media::getByFileable($id);
        return Image::getThumbnail($media, $this->thumbnailType, $code);
    }

    public function uploadImage(UploadedFile $uploadedFile, Storage $storage)
    {
        $file = $storage->upload($uploadedFile, static::FILE_UPLOAD_PATH);

        return $file;
    }

    public function createThumbnails($file, MediaManager $mediaManager)
    {
        $media = null;
        $thumbnails = null;
        if ($mediaManager->is($file) === true) {
            $media = $mediaManager->make($file);
            $thumbnails = $mediaManager->createThumbnails($media, $this->thumbnailType, $this->thumbnailDimensions);

            $media = $media->toArray();
        }

        return [$media, $thumbnails];
    }

    public function updateThumbnail(UploadedFile $uploadedFile, MediaManager $mediaManager, $id, $code)
    {
        /** @var ImageHandler $handler */
        $handler = $mediaManager->getHandler('image');

        $command = (new CommandFactory)->make($this->thumbnailType);
        list ($width, $height) = explode('x', $code);
        $command->setDimension(new Dimension($width, $height));

        /** @var Image $media */
        $media = Image::find($id);

        $config = app('config')['xe.media.thumbnail'];
        $thumbnail = $handler->createThumbnails(
            file_get_contents($uploadedFile->getPathname()),
            $command,
            $code,
            $config['disk'],
            $config['path'],
            $media->getOriginKey()
        );

        return $thumbnail;
    }

    public function add(array $args, Seller $seller)
    {
        $product = new Product();
        $product->getConnection()->beginTransaction();

        $product->fill($args);
        $product->seller_id = $seller->user_id;
        $product->product_image_id = $product->getKeyGen()->generate();
        $product->product_detail_image_id = $product->getKeyGen()->generate();

        if (isset($args['product_detail_image_file']) === false) {
            $args['product_detail_image_file'] = [];
        }

        $product->product_detail_image_order = implode(',', $args['product_detail_image_file']);
        $product->save();

        $this->setFiles($product, $args);
        $this->setCategory($product, [$args['category_item_id']]);
        $this->saveSlug($product, $args);
        $this->optionHandler->addDefault($product);

        $product->getConnection()->commit();
        return $product;
    }

    protected function setFiles(Product $product, array $args)
    {
        if (empty($args['product_image_file']) === false) {
            $this->storage->sync($product->product_image_id, $args['product_image_file']);
        }

        if (empty($args['product_detail_image_file']) === false) {
            $this->storage->sync($product->product_detail_image_id, $args['product_detail_image_file']);
        }

        if (empty($args['_files']) === false) {
            $this->storage->sync($product->getKey(), $args['_files']);
        }
    }

    protected function setCategory(Product $product, array $categoryItemIds)
    {
        /** @var \Illuminate\Support\Collection $items */
        $items = ProductCategory::where('product_id', $product->id)->value('selected_item_id');
        if ($items == []) {
            $oldItemIds = [];
        } else {
            $oldItemIds = $items->toArray();
        }

        $removes = array_diff($oldItemIds, $categoryItemIds);
        foreach ($removes as $id) {
            ProductCategory::where([
                'product_id' => $product->id,
                'selected_item_id' => $id,
            ])->delete();
        }

        foreach ($categoryItemIds as $categoryItemId) {
            if (in_array($categoryItemId, $oldItemIds) === true) {
                continue;
            }

            /** @var CategoryItem $categoryItem */
            $categoryItem = CategoryItem::find($categoryItemId);

            $model = new ProductCategory();
            $model->product_id = $product->id;
            $model->selected_item_id = $categoryItem->id;
            foreach ($categoryItem->getBreadcrumbs() as $index => $id) {
                $key = sprintf('item_id%s', $index + 1);
                $model->setAttribute($key, $id);
            }
            $model->save();
        }
    }

    protected function saveSlug(Product $product, array $args)
    {
        $model = $product->slug;
        if ($model === null) {
            $slug = Slug::make($product->product_name, $product->id);
            $model = new Slug([
                'target_id' => $product->id,
                'slug' => $slug,
                'title' => $product->product_name,
                'instance_id' => '',
            ]);
        } else {
            $model->slug = $args['slug'];
            $model->title = $product->product_name;
        }

        $model->save();
    }

    public function put(Product $product, array $args)
    {
        $product->getConnection()->beginTransaction();

        $attributes = $product->getAttributes();
        foreach ($args as $name => $value) {
            if (array_key_exists($name, $attributes)) {
                $product->{$name} = $value;
            }
        }
        if (isset($args['product_detail_image_file']) === false) {
            $args['product_detail_image_file'] = [];
        }

        $product->product_detail_image_order = implode(',', $args['product_detail_image_file']);
        $product->save();

        $this->setFiles($product, $args);

        if (isset($args['category_item_id'])) {
            $this->setCategory($product, $args['category_item_id']);
        }

        $this->saveSlug($product, $args);

        $product->getConnection()->commit();

        return $product;
    }

    public function filterResolver(Builder $query, Request $request)
    {
        if ($request->get('start_created_at', '') !== '') {
            $query->where('created_at', '>=', $request->get('start_created_at') . ' 00:00:00');
        }
        if ($request->get('end_created_at', '') !== '') {
            $query->where('created_at', '<=', $request->get('end_created_at') . ' 23:59:59');
        }

        $orderType = $request->get('order_type', '');
        if ($orderType !== '') {
            if ($orderType == '') {
                $query->orderBy('head', 'desc');
            } elseif ($orderType == 'assent_count') {
                $query->orderBy('assent_count', 'desc')->orderBy('head', 'desc');
            }
        }

        // dynamic query 지원 할 경우 추가 (아직 미정)
//        $query->getProxyManager()->wheres($query->getQuery(), $request->all());
//        $query->getProxyManager()->orders($query->getQuery(), $request->all());

        $this->filterCategoryResolver($query, $request);
    }

    public function filterCategoryResolver(Builder $query, Request $request)
    {
        if ($request->has('category_item_id') === true) {
            if ($request->has('with_lower_category_item') === true && $request->has('category_item_depth') === true) {
                $query->where('item_id' . $request->get('categoryItemDepth'), $request->get('category_item_id'));
            } else {
                $query->where('selected_item_id', $request->get('categoryItemId'));
            }

        }
    }
}
