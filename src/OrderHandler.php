<?php
namespace Akasima\RichShop;

use Akasima\RichShop\Models\Order;
use Akasima\RichShop\Models\OrderStatusLog;
use Xpressengine\Http\Request;
use Xpressengine\User\UserInterface;

class OrderHandler
{
    /** @var Request */
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function start(array $args, UserInterface $user)
    {
        // 주문 처리가 시작되면 Cart 의 정보가 주문 내역의 history 로 사용될 수 있도록 상태값을 제공해야 한다.
        $order = new Order();

        $order->getConnection()->beginTransaction();

        $order->fill($args);
        $order->user_id = $user->getId();
        $order->sum = $args['amount'] + $args['delivery_fee'];
        $order->ipaddress = $this->request->ip();;

        $order->save();

        $orderStatusLog = new OrderStatusLog();
        $orderStatusLog->order_id = $order->id;
        $orderStatusLog->status = Order::STATUS_START;
        $orderStatusLog->created_at = $orderStatusLog->freshTimestamp();
        $orderStatusLog->save();

        $order->getConnection()->commit();

        return $order;
    }

    public  function paymentComplete($orderId, $transactionId)
    {
        /** @var Order $order */
        $order = Order::find($orderId);
        $order->getConnection()->beginTransaction();
        $order->transaction_id = $transactionId;
        $order->status = Order::STATUS_PAYMENT_COMPLETE;
        $order->save();

        $orderStatusLog = new OrderStatusLog();
        $orderStatusLog->order_id = $order->id;

        $orderStatusLog->status = Order::STATUS_PAYMENT_COMPLETE;
        $orderStatusLog->created_at = $orderStatusLog->freshTimestamp();

        $order->getConnection()->commit();

        return $order;
    }
}
