<?php
namespace Akasima\RichShop\Modules;

use Route;

class Resources
{
    static public function routes()
    {
        static::usersRoutes();
    }

    static protected function usersRoutes()
    {
        Route::instance(Shop::getId(), function () {
            Route::get('/', ['as' => 'index', 'uses' => 'ModuleController@index']);
            Route::get('/product/{slug}', ['as' => 'product', 'uses' => 'ModuleController@product']);

        }, ['namespace' => 'Akasima\\RichShop\\Controllers']);
    }

    static public function getWidgetBoxId($instanceId)
    {
        return sprintf('rich_shop-%s', $instanceId);
    }
}
