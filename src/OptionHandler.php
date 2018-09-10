<?php
namespace Akasima\RichShop;

use Akasima\RichShop\Models\Option;
use Akasima\RichShop\Models\Product;

class OptionHandler
{
    public function __construct()
    {
    }

    /**
     * product 는 반드시 하나 이상의 option 을 갖어야 함
     *
     * @param Product $product
     * @return Option
     */
    public function addDefault(Product $product)
    {
        $params = [];
        $params['product_id'] = $product->id;
        $params['option_name'] = $product->product_name;
        $params['additional_price'] = 0;
        $params['parent_id'] = null;
        return $this->add($params);
    }

    /**
     * add option
     *
     * @param array $args arguments
     * @return Option
     * @throws \Exception
     */
    public function add(array $args)
    {
        if ($this->hasName($args['option_name'], $args['product_id'])) {
            throw new \Exception();
        }

        $option = new Option();
        $option->fill($args);
        $option->save();
        return $option;
    }

    /**
     * put option
     *
     * @param Option $option
     * @param array $args
     * @return Option
     * @throws \Exception
     */
    public function put(Option $option, array $args)
    {
        if ($this->hasName($args['option_name'], $args['product_id'], $args['id'])) {
            throw new \Exception();
        }

        $attributes = $option->getAttributes();
        foreach ($args as $name => $value) {
            if (array_key_exists($name, $attributes)) {
                $option->{$name} = $value;
            }
        }
        $option->save();
        return $option;
    }

    /**
     * 하나의 product 에 동일한 이름 사용할 수 없음
     * 수정인 경우 id 필요
     *
     * @param $name
     * @param $productId
     * @param null $id
     * @return bool
     */
    public function hasName($name, $productId, $id = null)
    {
        $origin = null;
        if ($id !== null) {
            $origin = Option::find($id);
        }

        $check = Option::where([
            'product_id' => $productId,
            'option_name' => $name,
        ])->first();

        if ($check === null) {
            return false;
        }

        // 기존의 이름과 같은 것인가?
        if ($origin !== null && $check !== null && $origin->id == $check->id) {
            return false;
        }

        return true;
    }
}
