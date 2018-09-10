<?php
namespace Akasima\RichShop\Models;

use Akasima\RichShop\Plugin;
use Akasima\RichShop\ProductHandler;
use App\Facades\XeStorage;
use Xpressengine\Database\Eloquent\DynamicModel;
use Xpressengine\Media\Models\Image;
use Xpressengine\Media\Models\Media;
use Xpressengine\Media\Repositories\ImageRepository;
use Xpressengine\Plugins\Comment\CommentUsable;
use Xpressengine\Routing\InstanceRoute;
use Xpressengine\Seo\SeoUsable;
use Xpressengine\Storage\File;
use Xpressengine\User\Models\Guest;
use Xpressengine\User\Models\UnknownUser;
use Xpressengine\User\Models\User;
use XeConfig;


class Product extends DynamicModel implements SeoUsable, CommentUsable
{
    public $table = 'rich_shop_products';

    public $incrementing = false;

    // sale
    const SALE_CLOSE = 0;
    const SALE_ON = 30;

    // display
    const DISPLAY_HIDDEN = 0;
    const DISPLAY_VISIBLE = 30;

    const PRODUCT_TYPE_NEW = 1;
    const PRODUCT_TYPE_USED = 2;
    const PRODUCT_TYPE_RETURN = 3;
    const PRODUCT_TYPE_REAPER = 4;
    const PRODUCT_TYPE_DISPLAYED = 5;
    const PRODUCT_TYPE_SCRATCH = 6;

    const TEXT_TYPE_TAXABLE = 1;    // 과세 상품
    const TEXT_TYPE_EXEMPT = 2; // 면세 상품
    const TEXT_TYPE_EXPORT = 3; // 영세 상품 (수출품, 매입ㅂ세액 환급 상품)

    protected $fillable = [
        'seller_id',
        'product_name', 'product_sub_name', 'product_real_name', 'product_model_name',
        'product_code', 'product_manage_code', 'product_type',
        'product_imageId', 'product_detail_image_id', 'product_detail_image_order',
        'description', 'sub_description', 'tags',
        'price', 'supply_price', 'margin_rate', 'margin_add_price', 'tax_type', 'tax_rate',
        'buy_item_limit', 'buy_item_limit_min', 'buy_item_limit_max',
        'display', 'sale',
    ];

    protected $casts = [
        'display' => 'int',
        'sale' => 'int',
    ];

    public function productTypes()
    {
        return [
            static::PRODUCT_TYPE_NEW => '신상품',
            static::PRODUCT_TYPE_USED => '중고',
            static::PRODUCT_TYPE_RETURN => '반품',
            static::PRODUCT_TYPE_REAPER => '리퍼',
            static::PRODUCT_TYPE_DISPLAYED => '전시',
            static::PRODUCT_TYPE_SCRATCH => '스크래치',
        ];
    }

    public function displayTypes()
    {
        return [
            static::DISPLAY_HIDDEN => '숨김',
            static::DISPLAY_VISIBLE => '출력',
        ];
    }

    public function saleTypes()
    {
        return [
            static::SALE_CLOSE => '판매중지',
            static::SALE_ON => '판매중',
        ];
    }

    public function saleTypeToText()
    {
        return $this->saleTypes()[$this->getAttribute('sale')];
    }

    public function displayTypeToText()
    {
        return $this->displayTypes()[$this->getAttribute('display')];
    }

    public function productTypeToText()
    {
        return $this->productTypes()[$this->getAttribute('product_type')];
    }

    public function productImageFile()
    {
        $files = File::getByFileable($this->product_image_id);

        if (count($files) === 0) {
            throw new \Exception('대표 사진을 찾을 수 없음');
        }
        return $files[0];
    }

    public function image()
    {
        /** @var Image[] $images */
        $images = Image::getByFileable($this->product_image_id);
        if (count($images) == 0) {
            return null;
        }

        $itemRepository = new ImageRepository();
        $thumbnail = $itemRepository->getThumbnail($images[0], 'fit', '200x200');

        return $thumbnail;
    }

    public function getThumbnail($code)
    {
        /** @var Image[] $images */
        $images = Image::getByFileable($this->product_image_id);
        if (count($images) == 0) {
            return null;
        }
        $thumbnail = Image::getThumbnail($images[0], 'fit', $code);
        return $thumbnail;
    }
    public function getDetailImage($imageId, $code = '200x200')
    {
        /** @var Image $imags */
        $image = Image::find($imageId);
        if ($image == null) {
            return null;
        }
        $thumbnail = Image::getThumbnail($image, 'fit', $code);
        return $thumbnail;
    }

    public function options()
    {
        return $this->hasMany(Option::class, 'product_id');
    }

    /**
     * 진열상품, 판매상품이 보여질 수 있도록
     *
     * @param $query
     */
    public function scopeVisible($query)
    {
        $query->where('sale', static::SALE_ON)
            ->where('display', static::DISPLAY_VISIBLE);
    }

    /**
     * get slug
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function slug()
    {
        return $this->hasOne(Slug::class, 'target_id');
    }

    /**
     * get users
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function seller()
    {
        return $this->hasOne(User::class, 'id', 'seller_id');
    }

    /**
     * Returns title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->getAttribute('product_name');
    }

    /**
     * Returns description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->getAttribute('description');
    }

    /**
     * Returns keyword
     *
     * @return string|array
     */
    public function getKeyword()
    {
        return [];
    }

    /**
     * Returns url
     *
     * @return string
     */
    public function getUrl()
    {
        return '';
    }

    /**
     * Returns author
     *
     * @return \Xpressengine\User\UserInterface
     */
    public function getAuthor()
    {
        if ($this->seller !== null) {
            return $this->seller;
        } elseif (\Auth::user() === false) {
            return new Guest;
        } else {
            return new UnknownUser;
        }
    }

    /**
     * Returns image url list
     *
     * @return array
     */
    public function getImages()
    {
        $files = File::getByFileable($this->getAttribute('product_image_id'));

        /** @var \Xpressengine\Media\MediaManager $mediaManager */
        $mediaManager = app('xe.media');
        $imageHandler = $mediaManager->getHandler(Media::TYPE_IMAGE);

        $images = [];
        foreach ($files as $file) {
            if ($mediaManager->getFileType($file) === Media::TYPE_IMAGE) {
                $images[] = $imageHandler->make($file);
            }
        }
        return $images;
    }

    /**
     * Returns unique identifier
     *
     * @return string
     */
    public function getUid()
    {
        return $this->getAttribute('id');
    }

    /**
     * Returns instance identifier
     *
     * @return mixed
     */
    public function getInstanceId()
    {
        return 'rich_shop';
    }

    /**
     * Returns the link
     *
     * @param InstanceRoute $route route instance
     * @return string
     */
    public function getLink(InstanceRoute $route)
    {
        $config = XeConfig::get(Plugin::getId());
        $userPrefix = $config->get('userPrefix');
        return $route->url . '/' . $userPrefix . '/' . $this->getKey();
    }
}
