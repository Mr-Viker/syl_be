<?php

namespace App\Models;

use App\Events\OrderUpdate;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{

  private static $status = [
    0 => '待付款',
    1 => '待发货',
    2 => '待收货',
    3 => '已完成',
    4 => '已取消',
  ];

  public const ORDER_WATI_PAY       = 0;
  public const ORDER_WATI_SEND      = 1;
  public const ORDER_WATI_CONFIRM   = 2;
  public const ORDER_SUCCESS        = 3;
  public const ORDER_CANCEL         = 4;

  // 订单-产品： 多对一
  public function goods() {
    return $this->belongsTo(Goods::class, 'goods_id');
  }

  // 订单-用户 多对一
  public function user() {
    return $this->belongsTo(User::class, 'user_id');
  }

  // 获取相应的状态文本
  public static function getStatusText($v) {
    return self::$status[$v];
  }

  // 获取所有状态
  public static function getAllStatus() {
    return self::$status;
  }

  // 支付成功后更新状态为待发货
  public function updatePaySuccessStatus() {
    return $this->updateOrderStatus(self::ORDER_WATI_SEND);
  }

  // 发货后
  public function updateSendStatus() {
    return $this->updateOrderStatus(self::ORDER_WATI_CONFIRM);
  }

  // 确认收货后
  public function confirm() {
    return $this->updateOrderStatus(self::ORDER_SUCCESS);
  }

  // 取消订单 待付款时
  public function cancel() {
    return $this->updateOrderStatus(self::ORDER_CANCEL);
  }

  // 更新订单状态
  public function updateOrderStatus($v) {
    if (empty($v) || !is_numeric($v)) {
      return false;
    }
    $this->status = $v;
    try {
      $res = $this->update();
      // 发出订单更新事件
      \Event::fire(new OrderUpdate($this));
    } catch(\Exception $e) {
      return false;
    }
    return $res;
  }

}
