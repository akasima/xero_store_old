<?php
namespace Akasima\RichShop\Widgets;

use Akasima\RichShop\Models\Product;
use Xpressengine\Media\Models\Image;
use Xpressengine\Widget\AbstractWidget;
use View;
use XeStorage;

class CrossWidget extends AbstractWidget
{
    protected static $viewAlias = 'rich_shop::views.widgets.cross';

    /**
     * Get the evaluated contents of the object.
     *
     * @return string
     */
    public function render()
    {
        $widgetConfig = $this->setting();


        $product1 = Product::find($widgetConfig['product1_id']);
        $product1Image = $this->getImage($product1, $widgetConfig['product1_file_id'], '367x238');
        $product2 = Product::find($widgetConfig['product2_id']);
        $product2Image = $this->getImage($product2, $widgetConfig['product2_file_id'], '367x490');
        $product3 = Product::find($widgetConfig['product3_id']);
        $product3Image = $this->getImage($product3, $widgetConfig['product3_file_id'], '367x238');
        $product4 = Product::find($widgetConfig['product4_id']);
        $product4Image = $this->getImage($product4, $widgetConfig['product4_file_id'], '367x490');

        return $view = View::make(sprintf('%s.widget', static::$viewAlias), [
            'widgetConfig' => $widgetConfig,
            'product1' => $product1,
            'product1Image' => $product1Image,
            'product2' => $product2,
            'product2Image' => $product2Image,
            'product3' => $product3,
            'product3Image' => $product3Image,
            'product4' => $product4,
            'product4Image' => $product4Image,
        ]);
    }

    /**
     * get image
     *
     * @param Product $product
     * @param $fileId
     * @param $code
     * @return null|\Xpressengine\Media\Models\Image
     */
    protected function getImage(Product $product, $fileId, $code)
    {
        $productImage = null;
        if ($fileId != '') {
            $productImage = $this->getThumb($fileId, $code);
        }
        if ($productImage == null) {
            $productImage = $product->getThumbnail('500x500');
        }

        return $productImage;
    }

    /**
     * get thumb
     *
     * @param $fileId
     * @param $code
     * @return null|Image
     */
    protected function getThumb($fileId, $code)
    {
        $thumb = null;
        /** @var Image $images */
        $image = Image::find($fileId);
        if ($image != null) {
            $thumb = Image::getThumbnail($image, 'fit', $code);
        }

        return $thumb;
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
        if (isset($args['product1_id']) === false) {
            $args['product1_id'] = '';
        }
        if (isset($args['product1_file_id']) === false) {
            $args['product1_file_id'] = '';
        }
        if (isset($args['product2_id']) === false) {
            $args['product2_id'] = '';
        }
        if (isset($args['product2_file_id']) === false) {
            $args['product2_file_id'] = '';
        }
        if (isset($args['product3_id']) === false) {
            $args['product3_id'] = '';
        }
        if (isset($args['product3_file_id']) === false) {
            $args['product3_file_id'] = '';
        }
        if (isset($args['product4_id']) === false) {
            $args['product4_id'] = '';
        }
        if (isset($args['product4_file_id']) === false) {
            $args['product4_file_id'] = '';
        }

        $product1Image = $this->getThumb($args['product1_file_id'], '367x238');
        $product2Image = $this->getThumb($args['product2_file_id'], '367x490');
        $product3Image = $this->getThumb($args['product3_file_id'], '367x238');
        $product4Image = $this->getThumb($args['product4_file_id'], '367x490');

        return $view = View::make(sprintf('%s.%s', static::$viewAlias, 'setting'), [
            'args' => $args,
            'product1Image' => $product1Image,
            'product2Image' => $product2Image,
            'product3Image' => $product3Image,
            'product4Image' => $product4Image,
        ]);
    }
}
