<?php
namespace Akasima\RichShop\Theme;

use Akasima\RichShop\Plugin;
use Xpressengine\Theme\GenericTheme;
use XeConfig;

class Theme extends GenericTheme
{
    protected static $path = 'xero_store/theme';

    /**
     * Get the evaluated contents of the object.
     *
     * @return string
     */
    public function render()
    {
        $shopConfig = XeConfig::get(Plugin::getId());
        self::$handler->getViewFactory()->share('shopConfig', $shopConfig);

        return parent::render();
    }

    public static function asset($path, $secure = null)
    {
        $path = 'plugins/xero_store/assets/' . $path;
        return asset($path, $secure);
    }
}
