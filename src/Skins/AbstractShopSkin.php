<?php
namespace Akasima\RichShop\Skins;

use Xpressengine\Skin\AbstractSkin;

/**
 * AbstractShopSkin
 *
 * 쇼핑몰 스킨 추상클래스
 *
 * @package Akasima\RichShop\Skins
 */
abstract class AbstractShopSkin extends AbstractSkin
{
    /**
     * 썸네일 코드를 반환합니다.
     * 코드는 썸네일의 px 사이즈를 의미합니다.
     * 코드는 'x'(엑스) 문자를 기준으로 앞에는 가로 사이즈 뒤에는 세로 사이즈를 입력합니다.
     * ex )
     * 100x100 은 가로 100px 세로 100px 입니다.
     * 400x300 은 가로 400px 세로 300px 입니다.
     *
     * @return string[]
     */
    public function getThumbnailCodes()
    {
        return [];
    }
}
