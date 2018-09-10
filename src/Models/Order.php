<?php
namespace Akasima\RichShop\Models;

use Xpressengine\Database\Eloquent\DynamicModel;
use Xpressengine\Plugins\Payment\Item;
use Xpressengine\Plugins\Payment\Order as OrderInterface;

class Order extends DynamicModel implements OrderInterface
{
    public $table = 'rich_shop_orders';

    public $incrementing = false;

    const PAYMENT_TYPE_CARD = 1;
    const PAYMENT_TYPE_CASH = 2;

    const STATUS_START = 0; // 주문 등록

    const STATUS_PAYMENT_COMPLETE = 500; // 결제 완료

    const STATUS_PREPARE_COMPLETE = 1100; // 상품 준비 완료

    const STATUS_CANCEL_COMPLETE = 3500; // 취소 완료 (상품이 배송되지 않고 결제 내역을 취소함)

    const STATUS_SHIPPING_COMPLETE = 4500; // 배송 완료

    const STATUS_RETURN_COMPLETE = 5500; // 반품 완료

    const STATUS_REFUND_COMPLETE = 6500; // 환불 완료

    const STATUS_BUY_COMPLETE = 8000; // 구매 완료 (구매자의 확인)


    protected $fillable = [
        'user_id', 'item_count', 'amount', 'shipping_fee', 'order_title',
        'recv_name', 'recv_phone', 'recv_postcode', 'recv_address1', 'recv_address2', 'shipping_memo',
    ];

    public function getOrderId()
    {
        return $this->id;
    }

    public function getOrderTitle()
    {
        return $this->order_title;
    }

    public function getPrice()
    {
        return $this->amount;
    }

    public function getItemCount()
    {
        // TODO: Implement getItemCount() method.
        return $this->item_count;
    }

    /**
     * @return Buyer
     */
    public function getBuyer()
    {
        // TODO: Implement getBuyer() method.
        $buyer = Buyer::find($this->getAttribute('user_id'));
        return $buyer;
    }

    /**
     * @return Item[]
     */
    public function getItems()
    {
        // TODO: Implement getItems() method.
    }
}
