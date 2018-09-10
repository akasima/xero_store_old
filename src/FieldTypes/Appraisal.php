<?php
namespace Akasima\RichShop\FieldTypes;

use Xpressengine\Config\ConfigEntity;
use Xpressengine\DynamicField\AbstractType;
use Xpressengine\DynamicField\ColumnDataType;
use Xpressengine\DynamicField\ColumnEntity;

/**
 * 구매평을 작성할 때 댓글 플러그인에 연결해서 평점을 처리할 목적으로 제작함
 *
 * Class Appraisal
 * @package Akasima\RichShop\FieldTypes
 */
class Appraisal extends AbstractType
{
    /**
     * get field type name
     *
     * @return string
     */
    public function name()
    {
        return 'Appraisal score';
    }

    /**
     * get field type description
     *
     * @return string
     */
    public function description()
    {
        // TODO: Implement description() method.
    }

    /**
     * return columns
     *
     * @return ColumnEntity[]
     */
    public function getColumns()
    {
        return [
            'score' => (new ColumnEntity('score', ColumnDataType::INTEGER)),
        ];
    }

    /**
     * return rules
     *
     * @return array
     */
    public function getRules()
    {
        return [
            'score' => 'numeric',
        ];
    }

    /**
     * 다이나믹필스 생성할 때 타입 설정에 적용될 rule 반환
     *
     * @return array
     */
    public function getSettingsRules()
    {
        return [];
    }

    /**
     * Dynamic Field 설정 페이지에서 각 fieldType 에 필요한 설정 등록 페이지 반환
     * return html tag string
     *
     * @param ConfigEntity $config config entity
     * @return string
     */
    public function getSettingsView(ConfigEntity $config = null)
    {
        return '';
    }
}
