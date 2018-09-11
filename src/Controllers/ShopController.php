<?php
namespace Akasima\RichShop\Controllers;

use Akasima\RichShop\Models\Cart;
use Akasima\RichShop\Models\CartOption;
use Akasima\RichShop\Models\Order;
use Akasima\RichShop\Models\Shipping;
use Akasima\RichShop\Models\Product;
use Akasima\RichShop\Models\ProductCategory;
use Akasima\RichShop\Models\Slug;
use Akasima\RichShop\Plugin;
use App\Http\Controllers\Controller;
use XePresenter;
use Xpressengine\Category\Models\CategoryItem;
use Xpressengine\Http\Request;
use Auth;
use Xpressengine\Plugins\Payment\PaymentManager;
use Xpressengine\Routing\InstanceConfig;
use Akasima\RichShop\Modules\Shop as ShopModule;
use XeConfig;
use XeTheme;

class ShopController extends Controller
{
    public function __construct()
    {
        XeTheme::selectTheme('theme/xero_store@theme');
        XePresenter::setSkinTargetId(Plugin::getId());
    }

    /** @var \Xpressengine\Routing\InstanceConfig */
    protected $instanceConfig;

    /**
     * @var string
     */
    protected $instanceId;

    /** @var  \Xpressengine\Config\ConfigEntity */
    protected $config;

    /**
     * items 메소드는 인스턴스 라우트
     *
     * @return mixed
     * @throws \Exception
     *
     * @todo test 안했음
     */
    public function index(Request $request)
    {
        // 인스턴스 라우트
        $this->instanceConfig = InstanceConfig::instance();
        $this->instanceId = $this->instanceConfig->getInstanceId();

        $this->config = XeConfig::get(sprintf('%s.%s', ItemsModule::getId(), $this->instanceId));
        $categoryItemId = $this->config->get('categoryItemId');
        if (!$this->config->get('categoryItemId')) {
            throw new \Exception('카테고리 아이템 아이디가 설정되지 않았습니다.');
        }

        XePresenter::setSkinTargetId(ShopModule::getId());
        XePresenter::share('instanceConfig', $this->instanceConfig);
        XePresenter::share('instanceId', $this->instanceId);
        XePresenter::share('config', $this->config);

        /** @var \Akasima\RichShop\ProductHandler $productHandler */
        $productHandler = app('xe.rich.shop.product');

        $categoryItem = CategoryItem::find($this->config->get('categoryItemId'));

        $model = new Product();
        $model->visible();
        $query = $model->newQuery();

        // join category table
        $categoryModel = new ProductCategory();
        $categoryQuery = $categoryModel->newQuery();

        $query->leftJoin(
            $categoryQuery->getQuery()->from,
            sprintf('%s.%s', $query->getQuery()->from, $model->getKeyName()),
            '=',
            sprintf('%s.%s', $categoryQuery->getQuery()->from, 'product_id')
        );

        $request->query->set('withLowerCategoryItem', '1');
        if ($request->has('categoryItemDepth') === false) {
            $request->query->set('categoryItemDepth', $this->config->get('categoryItemDepth'));
        }
        if ($request->has('categoryItemId') === false) {
            $request->query->set('categoryItemId', $this->config->get('categoryItemId'));
        }

        $productHandler->filterResolver($query, $request);

        $query->groupBy('id');
        $paginate = $query->paginate(30)->appends($request->except('page'));

        return XePresenter::make('items', [
            'categoryItem' => $categoryItem,
            'categoryChildren' => $categoryItem->getChildren(),
            'paginate' => $paginate,
        ]);
    }

    public function product(ShopService $service, $slug)
    {
        $data = $service->getProduct($slug);

        return XePresenter::make('product', $data);
    }

    /**
     * 상품 정보 페이지를 어디에 둬야 할지 결정하지 못했음
     * @param Request $request
     * @param $slug
     * @return mixed
     */
    public function products(Request $request, $slug)
    {
        $product = Slug::where('slug', $slug)->first()->product;

        if ($request->has('categoryItemId') == false) {
            $categoryItem = CategoryItem::find($this->config->get('categoryItemId'));
        } else {
            $categoryItem = CategoryItem::find($request->get('categoryItemId'));
        }
        $breadcrumbs = [];
        $ids = $categoryItem->getBreadcrumbs();
        foreach ($ids as $id) {
            $breadcrumbs[] = CategoryItem::find($id);
        }

        return XePresenter::make('product', [
            'categoryItem' => $categoryItem,
            'breadcrumbs' => $breadcrumbs,
            'product' => $product,
        ]);
    }

    /**
     * 카트에 등록
     *
     * @param Request $request
     */
    public function storeCart(Request $request)
    {
        // check productId

        /** @var \Akasima\RichShop\CartHandler $cartHandler */
        $cartHandler = app('xe.rich.shop.cart');

        /** @var \Xpressengine\User\UserInterface $user */
        $user = Auth::user();
        $inputs = $request->all();

        $cart = $cartHandler->add($inputs, $user);

        return XePresenter::makeApi([
            'cart' => $cart,
        ]);
    }

    public function cart(Request $request)
    {
        /** @var \Xpressengine\User\UserInterface $user */
        $user = Auth::user();

        $carts = Cart::where('user_id', $user->getId())->get();

        $summary = [
            'amount' => 0,
            'deliveryFee' => 0,
        ];

        foreach ($carts as $cart) {
            // 수량 또는 상품 판매 상태에 따른 예외 처리 해야함
            foreach ($cart->cartOptions as $cartOption) {
                $summary['amount'] += ($cart->product->price + $cartOption->option->additional_price) * $cartOption->quantity;
            }
        }

        return XePresenter::make('cart', [
            'carts' => $carts,
            'summary' => $summary,
        ]);
    }

    public function updateCartQuantity(Request $request, $id)
    {
        $cartOption = CartOption::find($id);

        /** @var \Akasima\RichShop\CartHandler $cartHandler */
        $cartHandler = app('xe.rich.shop.cart');
        $cartHandler->updateCartOptionQuantity($cartOption, $request->input('quantity'));

        $quantity = $cartOption->quantity;
        $amount = $cartOption->quantity * ($cartOption->product->price + $cartOption->option->additinal_price);
        $summary = [];

        return XePresenter::makeApi([
            'quantity' => $quantity,
            'amount' => $amount,
            'deliveryFee' => 0,
            'summary' => $summary,
        ]);
    }

    public function destroyCart(Request $request)
    {
        $deleteCartOptionItems = $request->get('checkItems');

        /** @var \Akasima\RichShop\CartHandler $cartHandler */
        $cartHandler = app('xe.rich.shop.cart');

        foreach ($deleteCartOptionItems as $cartOptionItem) {
            $cartHandler->removeCartOptionItem($cartOptionItem);
        }

        return app('xe.presenter')->makeApi(['type' => 'success', 'message' => '삭제가 완료되었습니다.']);
    }

    public function order(Request $request, PaymentManager $payment)
    {
        $cartOptionIds = $request->get('id');
        if (count($cartOptionIds) == 0) {
            throw new \Exception('선택된 주문상품이 없습니다.');
        }

        // 상품 상태 확인

        /** @var \Xpressengine\User\UserInterface $user */
        $user = Auth::user();

        $carts = Cart::where('userId', $user->getId())->get();

        $summary = [
            'amount' => 0,
            'shippingFee' => 0,
        ];
        foreach ($carts as $cart) {
            // 수량 또는 상품 판매 상태에 따른 예외 처리 해야함
            foreach ($cart->cartOptions as $cartOption) {
                if (in_array($cartOption->id, $cartOptionIds) === false) {
                    continue;
                }
                $summary['amount'] += ($cart->product->price + $cartOption->option->additinal_price) * $cartOption->quantity;
            }
        }

        $shippings = Shipping::where('userId', $user->getId())->where('type', '!=', Shipping::RECENT_TYPE)->get();
        $recentShipping = Shipping::where('userId', $user->getId())->where('type', Shipping::RECENT_TYPE)->first();
        $selectedShipping = 'new';

        $order = new Order([
            'userId' => $user->getId(),
            'amount' => $summary['amount'],
            'itemCount' => 3,
            'shippingFee' => $summary['shippingFee'],
            'orderTitle' => '상품명외 상품 몇건'
        ]);
        $payment->prepare($order);

        return XePresenter::make('order', [
            'carts' => $carts,
            'summary' => $summary,
            'cartOptionIds' => $cartOptionIds,
            'shippings' => $shippings,
            'recentShipping' => $recentShipping,
            'selectedShipping' => $selectedShipping,
            'payment' => $payment,
            'order' => $order,
        ]);
    }

    /**
     * 주문할 때 쿠폰이나 수량등을 변경하면 이 컨트롤러에서 정보를 다시 받는다.
     */
    public function orderCondition()
    {

    }

    /**
     * 주문을 시작하면 order insert 한다.
     * 결제 정보를 반환하고 결제 실행
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Exception
     */
    public function startPayment(Request $request)
    {
        /** @var \Akasima\RichShop\OrderHandler $orderHandler */
        $orderHandler = app('xe.rich.shop.order');

        /** @var \Xpressengine\User\UserInterface $user */
        $user = Auth::user();

        $cartOptions = CartOption::where('userId', $user->getId())->whereIn('id', $request->get('cartOptionId'))->get();
        $params = [
            'paymentType' => $request->get('paymentType'),
        ];
        $params['amount'] = 0;
        $params['deliveryFee'] = 0;
        $cartOptionIds = [];
        foreach ($cartOptions as $cartOption) {
            $cartOptionIds[] = $cartOption->id;
            $params['amount'] += ($cartOption->product->price + $cartOption->option->additinal_price) * $cartOption->quantity;
        }
        $params['cartOptionIds'] = implode(',', $cartOptionIds);

        // 배송지 정보
        if ($request->get('shippingType') === 'new') {
            $Shipping = Shipping::where('userId', $user->getId())->where('type', Shipping::RECENT_TYPE)->first();
            if ($Shipping === null) {
                $Shipping = new Shipping();
            }
            $Shipping->title = '최근 배송지';
            $Shipping->recvName = $request->get('recvName');
            $Shipping->recvPhone = $request->get('recvPhone');
            $Shipping->recvPostcode = $request->get('recvPostcode');
            $Shipping->recvAddress1 = $request->get('recvAddress1');
            $Shipping->recvAddress2 = $request->get('recvAddress2');
            $Shipping->type = Shipping::RECENT_TYPE;
            $Shipping->save();
        } else {
            $Shipping = Shipping::where('userId', $user->getId())->where('id', $request->get('shippingId'))->first();
        }
        if ($Shipping === null) {
            throw new \Exception('배송지 정보를 확인할 수 없습니다.');
        }
        $params = array_merge($params, $Shipping->toArray());

        // 주문 등록 처리
        $order = $orderHandler->start($params, $user);

        return XePresenter::makeApi([
            'id' => $order->id,
        ]);
    }


    public function completePayment(Request $request, PaymentManager $payment)
    {
        $response = $payment->approve($request);

        $orderId = null;
        $transactionId = null;
        if ($response->fails()) {
            // code for fail
            dump('fail');
            throw new \Exception('fail');
        } else {
            $orderId = $response->orderId();
            $transactionId = $response->transactionId();
        }

        if ($orderId === null || $transactionId == null) {
            throw new \Exception;
        }

        /** @var \Akasima\RichShop\OrderHandler $orderHandler */
        $orderHandler = app('xe.rich.shop.order');
        $order = $orderHandler->paymentComplete($orderId, $transactionId);

        // 장바구니 제거

        return XePresenter::redirect(route('rich_shop.payment.success', ['id' => $order->id]));
    }

    public function successPayment($id)
    {
        $order = Order::find($id);

        // 내 결제 인가?
        // 언제 완료 된건가?


        return XePresenter::make('success', [
            'order' => $order
        ]);
    }
}
