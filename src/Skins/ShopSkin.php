<?php
namespace Akasima\RichShop\Skins;

use Akasima\RichShop\Plugin;
use View;

/**
 * ShopSkin
 *
 * plugins/rich_shop/views/shop 디렉토리의 파일 사용
 *
 * @package Akasima\RichShop\Skins
 */
class ShopSkin extends AbstractShopSkin
{
    protected $basePath = 'views/shop';

    const Thumb1 = '450x600';
    const Thumb2 = '90x120';

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

    /**
     * @return array
     */
    public function getThumbnailCodes()
    {
        return [
            static::Thumb1,
            static::Thumb2,
        ];
    }
}
