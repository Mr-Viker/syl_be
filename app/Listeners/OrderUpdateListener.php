<?php
/**
 * 订单更新事件监听器
 */
namespace App\Listeners;

use App\Events\OrderUpdate;
use App\Models\Order;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class OrderUpdateListener
{
    private $order;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  OrderUpdate  $event
     * @return void
     */
    public function handle(OrderUpdate $event)
    {
        $this->order = $event->order;
        // 如果订单状态更新 则通知后台管理人员
        \Log::info("[================ 新订单通知: 有一笔待支付订单啦 ====================]\n
            [订单ID: {$this->order->id}]\n
            [=============================================================]
        ");


        // dd(Order::ORDER_WATI_PAY);
        // switch ($this->order->status) {
        //     case Order::ORDER_WATI_PAY:
        //         admin_toastr(trans('admin.order_wait_pay'));
        //         break;
            
        //     case Order::ORDER_WAIT_SEND:
        //         admin_toastr(trans('admin.order_wait_send'));
        //         break;
            
        //     case Order::ORDER_SUCCESS:
        //         admin_toastr(trans('admin.order_success'));
        //         break;

        //     case Order::ORDER_CANCEL:
        //         admin_toastr(trans('admin.order_cancel'));
        //         break;
        // }
    }
}
