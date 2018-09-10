<?php
namespace Akasima\RichShop;


use Akasima\RichShop\Models\Cart;
use Akasima\RichShop\Models\CartOption;
use Akasima\RichShop\Models\Option;
use Akasima\RichShop\Models\Product;
use App\Facades\XePresenter;
use Xpressengine\Http\Request;
use Xpressengine\User\UserInterface;

/**
 * 카트는 주문으로 넘어가기 전에 Order 로 전달할 데이터를 임시보관하는 곳
 * 구매가 완료되면 카트 데이터는 삭제한다. 카트의 데이터는  Order 에 보과되어 있을것
 *
 * Class CartHandler
 * @package Akasima\RichShop
 */
class CartHandler
{
    /** @var Request */
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function add(array $args, UserInterface $user)
    {
        $cart = new Cart();
        $product = Product::find($args['product_id']);

        if ($product === null) {
            throw new \Exception('product not founded');
        }
        // 재고 확인
        // ... 확인

        /** @var Option[] $options */
        $options = [];
        $amount = 0;
        $optionQuantity = 0;
        foreach ($args['order_option_id'] as $index => $optionId) {
            $option = Option::find($optionId);
            if ($option === null) {
                throw new \Exception('option not founded');
            }

            $options[] = $option;
            $amount += ($product->price + $option->additional_price) * $args['order_quantity'][$index];
            $optionQuantity += $args['order_quantity'][$index];
        }

        $cart->getConnection()->beginTransaction();

        $cart->user_id = $user->getId();
        $cart->product_id = $product->id;
        $cart->seller_id = $product->seller_id;
        $cart->amount = $amount;
        $cart->delivery_fee = 0;
        $cart->option_quantity = $optionQuantity;
        $cart->ipaddress = $this->request->ip();;
        $cart->save();

        foreach ($options as $index => $option) {
            $cartOption = new CartOption();
            $cartOption->cart_id = $cart->id;
            $cartOption->user_id = $cart->user_id;
            $cartOption->product_id = $cart->product_id;
            $cartOption->option_id = $option->id;
            $cartOption->quantity = $args['order_quantity'][$index];
            $cartOption->save();
        }

        $cart->getConnection()->commit();

        return $cart;
    }

    public function put(Cart $cart, array $args)
    {

    }

    public function updateCartOptionQuantity(CartOption $cartOption, $quantity)
    {
        $cartOption->getConnection()->beginTransaction();

        $cartOption->quantity = $quantity;
        $cartOption->save();

        $cartOptions = CartOption::where('cartId', $cartOption->cart_id);
        $cart = Cart::find($cartOption->cart_id);
        $product = Product::find($cart->product_id);

        $amount = 0;
        $optionQuantity = 0;

        foreach ($cartOptions as $option) {
            
        }
        $cartOption->getConnection()->commit();
    }

    public function removeCartOptionItem($cartOptionId)
    {
        $cartOption = CartOption::where('id', $cartOptionId)->first();

        $cart = $cartOption->cart;

        $cartOption->delete();

        if (count($cart->cartOptions) === 0) {
            $cart->delete();
        }
    }
}
