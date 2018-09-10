<?php
namespace Akasima\RichShop\Modules;

use Xpressengine\Category\Models\Category;
use Xpressengine\Menu\AbstractModule;
use Akasima\RichShop\Plugin;
use XeConfig;
use XeWidgetBox;
use View;

class Shop extends AbstractModule
{
    public static function boot()
    {
        Resources::routes();
    }

    /**
     * Return Create Form View
     * @return mixed
     */
    public function createMenuForm()
    {
        $config = XeConfig::get(Plugin::getId());
        $categoryItems = Category::find($config->get('categoryId'))->getProgenitors();

        return View::make(Plugin::getId() . '::views/shopModule/create', [
            'categoryItems' => $categoryItems,
        ])->render();
    }

    /**
     * Process to Store
     *
     * @param string $instanceId to store instance id
     * @param array $menuTypeParams for menu type store param array
     * @param array $itemParams except menu type param array
     *
     * @return mixed
     * @internal param $inputs
     *
     */
    public function storeMenu($instanceId, $menuTypeParams, $itemParams)
    {
        // 카테고리 선택을 필수로 해야함. 카테고리 id 만으로는 설정할 수 없음

        // TODO: Implement storeMenu() method.
        XeConfig::add(sprintf('%s.%s', static::getId(), $instanceId), [
            'categoryItemId' => $menuTypeParams['categoryItemId'],
            'categoryItemDepth' => $menuTypeParams['categoryItemDepth'],
        ]);

        // 위젯 박스 추가
        $id = Resources::getWidgetBoxId($instanceId);
        $widgetBox = XeWidgetBox::find($id);
        if($widgetBox === null) {
            $widgetBox = XeWidgetBox::create(['id'=>$id, 'title'=>xe_trans($itemParams['title']), 'content'=> '']);
        }
    }

    /**
     * Return Edit Form View
     *
     * @param string $instanceId to edit instance id
     *
     * @return mixed
     */
    public function editMenuForm($instanceId)
    {
        // TODO: Implement editMenuForm() method.
        return '';
    }

    /**
     * Process to Update
     *
     * @param string $instanceId to update instance id
     * @param array $menuTypeParams for menu type update param array
     * @param array $itemParams except menu type param array
     *
     * @return mixed
     * @internal param $inputs
     *
     */
    public function updateMenu($instanceId, $menuTypeParams, $itemParams)
    {
        // TODO: Implement updateMenu() method.
    }

    /**
     * displayed message when menu is deleted.
     *
     * @param string $instanceId to summary before deletion instance id
     *
     * @return string
     */
    public function summary($instanceId)
    {
        // TODO: Implement summary() method.
    }

    /**
     * Process to delete
     *
     * @param string $instanceId to delete instance id
     *
     * @return mixed
     */
    public function deleteMenu($instanceId)
    {
        // TODO: Implement deleteMenu() method.

        // remove widget box
        $id = Resources::getWidgetBoxId($instanceId);

        $widgetBox = XeWidgetBox::find($id);
        XeWidgetBox::delete($widgetBox);
    }

    /**
     * Get menu type's item object
     *
     * @param string $id item id of menu type
     * @return mixed
     */
    public function getTypeItem($id)
    {
        // TODO: Implement getTypeItem() method.
    }

    /**
     * Return URL about module's detail setting
     * getInstanceSettingURI
     *
     * @param string $instanceId instance id
     * @return mixed
     */
    public static function getInstanceSettingURI($instanceId)
    {
        return route('rich_shop.settings.items.edit', $instanceId);
    }
}
