<?php
namespace Akasima\RichShop\Widgets;

use Akasima\RichShop\Models\Product;
use Xpressengine\Media\Models\Image;
use Xpressengine\Widget\AbstractWidget;
use View;

class TabWidget extends AbstractWidget
{
    protected static $viewAlias = 'rich_shop::views.widgets.tab';

    /**
     * Get the evaluated contents of the object.
     *
     * @return string
     */
    public function render()
    {
        $widgetConfig = $this->setting();

        $tabItems = $this->getTabItemsData($widgetConfig);

        return $view = View::make(sprintf('%s.widget', static::$viewAlias), [
            'widgetConfig' => $widgetConfig,
            'tabItems' => $tabItems,
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

        if (isset($args['tab_name']) === false) {
            $args['tab_name'] = [];
            $args['tab_name']['item'] = [];
        }

        if (isset($args['product0_id']) === false) {
            $args['product0_id'] = [];
            $args['product0_id']['item'] = [];
        }
        if (isset($args['product1_id']) === false) {
            $args['product1_id'] = [];
            $args['product1_id']['item'] = [];
        }
        if (isset($args['product2_id']) === false) {
            $args['product2_id'] = [];
            $args['product2_id']['item'] = [];
        }

        $tabItems = $this->getTabItemsData($args);

        return $view = View::make(sprintf('%s.%s', static::$viewAlias, 'setting'), [
            'args' => $args,
            'tabItems' => $tabItems
        ]);
    }

    protected function getTabItemsData(array $config)
    {
        $items = [];

        $itemCount = count($config['tab_name']['item']);
        
        if ($itemCount == 1) {
            $config['tab_name']['item'] = [$config['tab_name']['item']];
            $config['product0_id']['item'] = [$config['product0_id']['item']];
            $config['product0_file_id']['item'] = [$config['product0_file_id']['item']];
            $config['product1_id']['item'] = [$config['product1_id']['item']];
            $config['product1_file_id']['item'] = [$config['product1_file_id']['item']];
            $config['product2_id']['item'] = [$config['product2_id']['item']];
            $config['product2_file_id']['item'] = [$config['product2_file_id']['item']];
        }

        for ($i=0; $i<$itemCount; $i++) {
            
            $items[] = [
                'tab_name' => $config['tab_name']['item'][$i],
                
                'product0_id' => $config['product0_id']['item'][$i],
                'product0_file_id' => $config['product0_file_id']['item'][$i],
                'product0' => Product::find($config['product0_id']['item'][$i]),
                'product0_image' => $this->getImage($config['product0_file_id']['item'][$i]),

                'product1_id' => $config['product1_id']['item'][$i],
                'product1_file_id' => $config['product1_file_id']['item'][$i],
                'product1' => Product::find($config['product1_id']['item'][$i]),
                'product1_image' => $this->getImage($config['product1_file_id']['item'][$i]),

                'product2_id' => $config['product2_id']['item'][$i],
                'product2_file_id' => $config['product2_file_id']['item'][$i],
                'product2' => Product::find($config['product2_id']['item'][$i]),
                'product2_image' => $this->getImage($config['product2_file_id']['item'][$i]),
            ];
        }
        
        return $items;
    }

    /**
     * get image
     *
     * @param $fileId
     * @return null
     */
    protected function getImage($fileId)
    {
        $productImage = null;

        if ($fileId != '') {
            $image = Image::find($fileId);
            $productImage = Image::getThumbnail($image, 'fit', '369x240');
        }

        return $productImage;
    }
}
