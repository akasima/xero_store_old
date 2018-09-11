<?php
/**
 * ItemsController
 */
namespace Akasima\RichShop\Controllers;

use Akasima\RichShop\Models\Product;
use Akasima\RichShop\Models\ProductCategory;
use Akasima\RichShop\Models\Slug;
use Akasima\RichShop\Modules\Resources;
use Akasima\RichShop\Plugin;
use Akasima\RichShop\Modules\Shop as ShopModule;
use App\Http\Controllers\Controller;
use XePresenter;
use XeFrontend;
use XeConfig;
use XeTheme;
use XeWidgetBox;
use Xpressengine\Category\Models\CategoryItem;
use Xpressengine\Config\ConfigEntity;
use Xpressengine\Http\Request;
use Xpressengine\Routing\InstanceConfig;

/**
 * Class ItemsController
 *
 * @package Akasima\RichShop\Controllers
 */
class ModuleController extends Controller
{
    /** @var ConfigEntity */
    protected $config;

    /** @var  string */
    protected $instanceId;

    public function __construct()
    {
        $instanceConfig = InstanceConfig::instance();
        $instanceId = $instanceConfig->getInstanceId();
        $config = XeConfig::get(sprintf('%s.%s', ShopModule::getId(), $instanceId));
        if (!$config->get('categoryItemId')) {
            throw new \Exception('카테고리 아이템 아이디가 설정되지 않았습니다.');
        }

        $this->config = $config;
        $this->instanceId = $instanceId;

        XePresenter::setSkinTargetId(ShopModule::getId());
        XePresenter::share('config', $config);

        \XeTheme::selectTheme('theme/xero_store@theme');
    }

    /**
     * instance controller 를 통해 접근한 페이지 처리
     *
     * @param ShopService $service
     * @return mixed
     * @throws \Exception
     */
    public function index(ShopService $service)
    {
        $data = $service->getItems($this->config);

        $id = Resources::getWidgetBoxId($this->instanceId);
        $widgetBox = XeWidgetBox::find($id);
        $data['widgetBox'] = $widgetBox;

        return XePresenter::make('index', $data);
    }

    public function product(ShopService $service, $menuUrl, $slug)
    {
        $data = $service->getProduct($slug, $this->config);

        XeFrontend::title(sprintf(
            '%s - %s',
            $data['product']->productName,
            xe_trans(XeFrontend::output('title'))
        ));

        return XePresenter::make('product', $data);
    }
}
