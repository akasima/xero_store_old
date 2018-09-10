<?php
namespace Akasima\RichShop\Skins;

use Akasima\RichShop\Plugin;
use Xpressengine\Skin\AbstractSkin;
use View;

/**
 * ShopSkin
 *
 * plugins/rich_shop/views/shop 디렉토리의 파일 사용
 *
 * @package Akasima\RichShop\Skins
 */
class SettingsSkin extends AbstractSkin
{
    protected $basePath = 'views/settings';

    protected function getSkinAlias()
    {
        return sprintf('%s::%s', Plugin::getId(), $this->basePath);
    }

    public function render()
    {
        return View::make(
            sprintf('%s.%s', $this->getSkinAlias(), $this->view),
            $this->data
        );
    }
}
