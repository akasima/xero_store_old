<?php
namespace Akasima\RichShop\Theme;

use Akasima\RichShop\Plugin;
use Xpressengine\Theme\GenericTheme;
use XeConfig;

class Theme extends GenericTheme
{
    protected static $path = 'rich_shop/theme';

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
}
