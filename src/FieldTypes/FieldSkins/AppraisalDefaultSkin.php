<?php
namespace Akasima\RichShop\FieldTypes\FieldSkins;

use Xpressengine\DynamicField\AbstractSkin;

class AppraisalDefaultSkin extends AbstractSkin
{
    /**
     * get name of skin
     *
     * @return string
     */
    public function name()
    {
        return 'default';
    }

    /**
     * get view file directory path
     *
     * @return string
     */
    public function getPath()
    {
        return 'rich_shop::views.dynamicField.appraisal.default';
    }

    /**
     * 다이나믹필스 생성할 때 스킨 설정에 적용될 rule 반환
     *
     * @return array
     */
    public function getSettingsRules()
    {
        return [];
    }
}
