<?php
namespace Akasima\RichShop;

use Akasima\RichShop\FieldTypes\Appraisal;
use Akasima\RichShop\FieldTypes\FieldSkins\AppraisalDefaultSkin;
use Akasima\RichShop\Modules\Shop as ShopModule;
use App\Http\Middleware\ExceptAppendableVerifyCsrfToken;
use XeDynamicField;
use App\Facades\XeInterception;
use Route;
use XeConfig;
use XeRegister;
use XeCategory;
use XeEditor;
use XeLang;
use XeDB;
use Xpressengine\Plugins\CkEditor\Editors\CkEditor;

/**
 * Resources
 *
 * 자원 등록 및 관리
 *
 * @package Akasima\RichShop
 */
class Resources
{
    static public function bindClasses()
    {
        /** @var \Illuminate\Foundation\Application $app */
        $app = app();

        $app->singleton(ProductHandler::class, function ($app) {
            $proxyHandler = XeInterception::proxy(ProductHandler::class);

            $handler = new $proxyHandler(
                app('xe.storage'),
                app('xe.rich.shop.option')
            );
            return $handler;
        });
        $app->alias(ProductHandler::class, 'xe.rich.shop.product');

        $app->singleton(OptionHandler::class, function ($app) {
            $proxyHandler = XeInterception::proxy(OptionHandler::class);

            $handler = new $proxyHandler();
            return $handler;
        });
        $app->alias(OptionHandler::class, 'xe.rich.shop.option');

        $app->singleton(CartHandler::class, function ($app) {
            $proxyHandler = XeInterception::proxy(CartHandler::class);

            $handler = new $proxyHandler(
                app('request')
            );
            return $handler;
        });
        $app->alias(CartHandler::class, 'xe.rich.shop.cart');

        $app->singleton(OrderHandler::class, function ($app) {
            $proxyHandler = XeInterception::proxy(OrderHandler::class);

            $handler = new $proxyHandler(
                app('request')
            );
            return $handler;
        });
        $app->alias(OrderHandler::class, 'xe.rich.shop.order');
    }

    /**
     * add default config
     *
     * @return void
     */
    static public function setConfig()
    {
        // CK 에디터 사용 설정
        XeEditor::setInstance(Plugin::getId(), CkEditor::getId());

        // 상품 분류 카테고리 추가
        $category = XeCategory::create([
            'name' => '상품 분류'
        ]);

        // 쇼핑몰 설정
        XeConfig::add(Plugin::getId(), [
            'userPrefix' => 'shop',
            'categoryId' => $category->id,
        ]);

        // items 모듈 기본 설정
        XeConfig::add(ShopModule::getId(), [
            'skin' => ''
        ]);
    }

    static public function migration()
    {

    }

    /**
     * install 할 때 comment instance 생성
     *
     * @return void
     */
    static public function createCommentInstance()
    {
        /** @var \Xpressengine\Plugins\Comment\Handler $commentHandler */
        $commentHandler = app('xe.plugin.comment')->getHandler();
        $commentHandler->createInstance(Plugin::instanceId, true);
        $commentHandler->configure($commentHandler->getInstanceId(Plugin::instanceId), ['useWysiwyg' => true]);
        static::createAppraisalScoreField($commentHandler->getInstanceId(Plugin::instanceId));

        $instanceId = Plugin::instanceId . '_qna';
        /** @var \Xpressengine\Plugins\Comment\Handler $commentHandler */
        $commentHandler = app('xe.plugin.comment')->getHandler();
        $commentHandler->createInstance($instanceId, true);
        $commentHandler->configure($commentHandler->getInstanceId($instanceId), ['useWysiwyg' => true]);
    }

    /**
     * 구매평 다이나믹필드
     *
     * @param string $instanceId instance id
     * @return void
     */
    static public function createAppraisalScoreField($instanceId)
    {
        /** @var \Xpressengine\DynamicField\ConfigHandler $configHandler */
        $configHandler = XeDynamicField::getConfigHandler();
        $config = $configHandler->getDefault();
        $config->set('required', 'true');
        $config->set('sortable', 'true');
        $config->set('searchable', 'true');
        $config->set('use', 'true');
        $config->set('group', 'comment_' . $instanceId);
        $config->set('typeId', Appraisal::getId());
        $config->set('id', 'appraisal');

        $label = XeLang::genUserKey();
        foreach (XeLang::getLocales() as $locale) {
            $value = "만족도";
            if ($locale != 'ko') {
                $value = "Satisfaction";
            }
            XeLang::save($label, $locale, $value, false);
        }
        $config->set('label', $label);
        $config->set('skinId', AppraisalDefaultSkin::getId());
        $config->set('joinColumnName', 'id');

        XeDynamicField::setConnection(XeDB::connection());
        XeDynamicField::create($config);
    }

    static public function routes()
    {
        static::shopRoutes();
        static::settingsRoutes();
    }

    /**
     * 결제, 카트, ..
     */
    static protected function shopRoutes()
    {
        $config = XeConfig::get(Plugin::getId());
        $userPrefix = $config->get('userPrefix');

        // $userPrefix 가 메뉴 아이템에 사용되어 충돌 나지 않도록 처리
        config(['xe.routing' => array_merge(
            config('xe.routing'), ['rich_shop' => $userPrefix]
        )]);


        Route::group(['middleware' => ['web', 'auth'], 'prefix' => $userPrefix, 'namespace' => 'Akasima\\RichShop\\Controllers'], function()
        {
            Route::get('/', ['as' => 'rich_shop.index', 'uses' => 'ShopController@index']);
            Route::get('/product/{slug}', ['as' => 'rich_shop.product', 'uses' => 'ShopController@product']);
            Route::get('buy/{slug}', ['as' => 'rich_shop.buy', 'uses' => 'ShopController@buy']);

            Route::get('order', ['as' => 'rich_shop.order', 'uses' => 'ShopController@order']);
            Route::post('payment/start', ['as' => 'rich_shop.payment.start', 'uses' => 'ShopController@startPayment']);
            Route::get('payment/complete', ['as' => 'rich_shop.payment.complete', 'uses' => 'ShopController@completePayment']);
            Route::post('payment/complete', ['as' => 'rich_shop.payment.complete', 'uses' => 'ShopController@completePayment']);
            Route::get('payment/success/{id}', ['as' => 'rich_shop.payment.success', 'uses' => 'ShopController@successPayment']);

            Route::get('cart', ['as' => 'rich_shop.cart', 'uses' => 'ShopController@cart']);
            Route::post('cart/store/', ['as' => 'rich_shop.cart.store', 'uses' => 'ShopController@storeCart']);
            Route::post('cart/destroy/', ['as' => 'rich_shop.cart.destroy', 'uses' => 'ShopController@destroyCart']);
            Route::post('cart/{id}/quantity/update', ['as' => 'rich_shop.cart.quantity.update', 'uses' => 'ShopController@updateCartQuantity']);
            Route::get('cart/amount', ['as' => 'rich_shop.cart.amount', 'uses' => 'ShopController@getCartAmount']);

            Route::get('favorite', ['as' => 'rich_shop.favorite', 'uses' => 'ShopController@favorite']);
            Route::post('favorite/store/', ['as' => 'rich_shop.favorite.store', 'uses' => 'ShopController@storeFavorite']);
            Route::post('favorite/destroy/', ['as' => 'rich_shop.favorite.destroy', 'uses' => 'ShopController@destroyFavorite']);
        });

        $routes = app('router')->getRoutes();
        $route =  $routes->getByName('rich_shop.payment.complete');
//        ExceptAppendableVerifyCsrfToken::setExcept($route->getPath());
    }

    /**
     * 상품 관리
     */
    static protected function settingsRoutes()
    {
        Route::settings(Plugin::getId(), function () {
            Route::post('/widget/imageUpload/{size}', [
                'as' => 'rich_shop.settings.widget.image.upload',
                'uses' => 'SettingsController@widgetImageUpload',
            ]);

            Route::get('/configure/shopInfo', [
                'as' => 'rich_shop.settings.configure.shopInfo',
                'uses' => 'SettingsController@shopInfo',
                'settings_menu' => 'rich_shop.configure.shopInfo',
            ]);
            Route::post('/configure/shopInfo/store', [
                'as' => 'rich_shop.settings.configure.shopInfo.store',
                'uses' => 'SettingsController@storeShopInfo',
            ]);

            Route::get('/configure/category', [
                'as' => 'rich_shop.settings.configure.category',
                'uses' => 'SettingsController@category',
                'settings_menu' => 'rich_shop.configure.category',
            ]);
            Route::post('/configure/category/create', [
                'as' => 'rich_shop.settings.configure.category.store',
                'uses' => 'SettingsController@storeCategory',
            ]);


            Route::get('/product/create', [
                'as' => 'rich_shop.settings.product.create',
                'uses' => 'SettingsController@createProduct',
                'settings_menu' => 'rich_shop.product.create',
            ]);
            Route::post('/product/store', [
                'as' => 'rich_shop.settings.product.store',
                'uses' => 'SettingsController@storeProduct',
            ]);
            Route::post('/product/image/upload/', [
                'as' => 'rich_shop.settings.product.image.upload',
                'uses' => 'SettingsController@uploadProductImage',
            ]);
            Route::post('/product/image/thumbnail/{code}', [
                'as' => 'rich_shop.settings.product.thumbnail.upload',
                'uses' => 'SettingsController@uploadProductThumbnail',
            ]);

            Route::get('/product/list', [
                'as' => 'rich_shop.settings.product.list',
                'uses' => 'SettingsController@productList',
                'settings_menu' => 'rich_shop.product.list',
            ]);

            Route::get('/product/{id}/edit', [
                'as' => 'rich_shop.settings.product.edit',
                'uses' => 'SettingsController@editProduct',
            ]);
            Route::post('/product/{id}/update', [
                'as' => 'rich_shop.settings.product.update',
                'uses' => 'SettingsController@updateProduct',
            ]);
            Route::post('/product/{productId}/option/store', [
                'as' => 'rich_shop.settings.product.option.store',
                'uses' => 'SettingsController@storeOption',
            ]);
            Route::post('/product/{productId}/option/{id}/update', [
                'as' => 'rich_shop.settings.product.option.update',
                'uses' => 'SettingsController@updateOption',
            ]);


//            Route::get('/configure', [
//                'as' => 'rich_shop.settings.configure.index',
//                'uses' => 'SettingsController@configure',
//                'settings_menu' => 'rich_shop.configure.index',
//            ]);
//            Route::post('/configure/store', [
//                'as' => 'rich_shop.settings.configure.store',
//                'uses' => 'SettingsController@storeConfigure',
//            ]);

            Route::get('/order', [
                'as' => 'rich_shop.settings.order.index',
                'uses' => 'SettingsController@order',
                'settings_menu' => 'rich_shop.order.index',
            ]);

            // instance settings route
            Route::get('/edit/{id}', ['as' => 'rich_shop.settings.items.edit', 'uses' => 'SettingsController@editItemsModule']);
            Route::post('/update/{id}', ['as' => 'rich_shop.settings.items.update', 'uses' => 'SettingsController@updateItemsModule']);

        }, ['namespace' => 'Akasima\\RichShop\\Controllers']);
    }

    /**
     * 관리자에 메뉴 등록
     */
    static public function settingsMenu()
    {
        $menus = array_merge(
            [
                'rich_shop' => [
                    'title' => '쇼핑몰 관리',
                    'display' => true,
                    'description' => '',
                    'ordering' => 10000
                ],
            ],
            static::menuConfigure(),
            static::menuProduct(),
            static::menuOrder(),
            [
                'rich_shop.marketing' => [
                    'title' => '마케팅',
                    'display' => true,
                    'description' => '',
                    'ordering' => 10004
                ],
                'rich_shop.marketing.point' => [
                    'title' => '포인트',
                    'display' => true,
                    'description' => '',
                    'ordering' => 100011
                ],
                'rich_shop.marketing.coupon' => [
                    'title' => '쿠폰',
                    'display' => true,
                    'description' => '',
                    'ordering' => 100012
                ],
                'rich_shop.marketing.message' => [
                    'title' => '메시지',
                    'display' => true,
                    'description' => '',
                    'ordering' => 100013
                ],
                'rich_shop.marketing.banner' => [
                    'title' => '배너',
                    'display' => true,
                    'description' => '',
                    'ordering' => 100014
                ],
                'rich_shop.marketing.recommend' => [
                    'title' => '추천상품',
                    'display' => true,
                    'description' => '',
                    'ordering' => 100015
                ],
                'rich_shop.stats' => [
                    'title' => '통계',
                    'display' => true,
                    'description' => '',
                    'ordering' => 10005
                ],
                'rich_shop.stats.sales' => [
                    'title' => '매출',
                    'display' => true,
                    'description' => '',
                    'ordering' => 100011
                ],
                'rich_shop.stats.estimated' => [
                    'title' => '추정 매출',
                    'display' => true,
                    'description' => '',
                    'ordering' => 100012
                ],
            ]
        );

        foreach ($menus as $id => $menu) {
            XeRegister::push('settings/menu', $id, $menu);
        }
    }

    static private function menuConfigure()
    {
        return [
            'rich_shop.configure' => [
                'title' => '환경설정',
                'display' => true,
                'description' => '',
                'ordering' => 10001
            ],

            'rich_shop.configure.shopInfo' => [
                'title' => '정보 등록(필수)',
                'display' => true,
                'description' => '',
                'ordering' => 100011
            ],
            'rich_shop.configure.category' => [
                'title' => '분류',
                'display' => true,
                'description' => '',
                'ordering' => 100012
            ],
//
//            'rich_shop.configure.index' => [
//                'title' => '기본 설정',
//                'display' => true,
//                'description' => '',
//                'ordering' => 100011
//            ],
//            'rich_shop.configure.payment' => [
//                'title' => '결제 설정',
//                'display' => true,
//                'description' => '',
//                'ordering' => 100011
//            ],
//            'rich_shop.configure.rules' => [
//                'title' => '규칙 정보',
//                'display' => true,
//                'description' => '',
//                'ordering' => 100011
//            ],
        ];
    }

    static private function menuOrder()
    {
        return [
            'rich_shop.order' => [
                'title' => '주문',
                'display' => true,
                'description' => '',
                'ordering' => 10002
            ],
            'rich_shop.order.index' => [
                'title' => '전체 주문내역',
                'display' => true,
                'description' => '',
                'ordering' => 100011
            ],
            'rich_shop.order.bank' => [
                'title' => '무통장 입금',
                'display' => true,
                'description' => '',
                'ordering' => 100012
            ],
            'rich_shop.order.delivery_ready' => [
                'title' => '배송 준비',
                'display' => true,
                'description' => '',
                'ordering' => 100013
            ],
            'rich_shop.order.sending' => [
                'title' => '배송중',
                'display' => true,
                'description' => '',
                'ordering' => 100014
            ],
            'rich_shop.order.cancel' => [
                'title' => '결제 취소',
                'display' => true,
                'description' => '',
                'ordering' => 100015
            ],
            'rich_shop.order.exchange' => [
                'title' => '교환 요청',
                'display' => true,
                'description' => '',
                'ordering' => 100016
            ],
            'rich_shop.order.refund' => [
                'title' => '환불 요청',
                'display' => true,
                'description' => '',
                'ordering' => 100017
            ],
            'rich_shop.order.receipt' => [
                'title' => '영수증 관리',
                'display' => true,
                'description' => '',
                'ordering' => 100018
            ],
        ];
    }

    static private function menuProduct()
    {
        return [
            'rich_shop.product' => [
                'title' => '상품관리',
                'display' => true,
                'description' => '',
                'ordering' => 10003
            ],
            'rich_shop.product.list' => [
                'title' => '전체 상품목록',
                'display' => true,
                'description' => '',
                'ordering' => 100011
            ],
            'rich_shop.product.create' => [
                'title' => '상품 등록',
                'display' => true,
                'description' => '',
                'ordering' => 100012
            ],
            'rich_shop.product.company' => [
                'title' => '업체',
                'display' => true,
                'description' => '',
                'ordering' => 100013
            ],
        ];
    }
}
