<?php
namespace Akasima\RichShop\Widgets;

use Akasima\RichShop\Models\Product;
use Xpressengine\Widget\AbstractWidget;
use View;

class ListWidget extends AbstractWidget
{
    protected static $viewAlias = 'rich_shop::views.widgets.list';

    /**
     * Get the evaluated contents of the object.
     *
     * @return string
     */
    public function render()
    {
        $widgetConfig = $this->setting();

        $items = $this->getItemsData($widgetConfig);

        return $view = View::make(sprintf('%s.widget', static::$viewAlias), [
            'widgetConfig' => $widgetConfig,
            'items' => $items,
        ]);
    }

    /**
     * 위젯 설정 페이지에 출력할 폼을 출력한다.
     *
     * @param array $args 설정값
     *
     * @return string
     */
    public function renderSetting(array $args = [])
    {
        if (isset($args['title']) === false) {
            $args['title'] = '';
        }
        if (isset($args['product_id']) === false) {
            $args['product_id'] = [];
            $args['product_id']['item'] = [];
        }

        $items = $this->getItemsData($args);

        return $view = View::make(sprintf('%s.%s', static::$viewAlias, 'setting'), [
            'args' => $args,
            'items' => $items,
        ]);
    }

    protected function getItemsData(array $config)
    {
        $items = [];

        $itemCount = count($config['product_id']['item']);

        if ($itemCount == 1) {
            $config['product_id']['item'] = [$config['product_id']['item']];
        }

        for ($i=0; $i<$itemCount; $i++) {
            $items[] = [
                'product_id' => $config['product_id']['item'][$i],
                'product' => Product::find($config['product_id']['item'][$i]),
            ];
        }

        return $items;
    }

}
