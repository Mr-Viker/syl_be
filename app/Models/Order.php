<?php

namespace App\Models;

use App\Events\OrderUpdate;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{

  // protected $dateFormat = 'U';

  private static $status = [
    0 => '待付款',
    1 => '待发货',
    2 => '待收货',
    3 => '已完成',
    4 => '已取消',
  ];

  public const ORDER_WAIT_PAY       = 0;
  public const ORDER_WAIT_SEND      = 1;
  public const ORDER_WAIT_CONFIRM   = 2;
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
    $res = $this->updateOrderStatus(self::ORDER_WAIT_SEND);
    // 发出订单更新事件
    // \Event::fire(new OrderUpdate($this));
    return $res;
  }

  // 发货后
  public function updateSendStatus() {
    return $this->updateOrderStatus(self::ORDER_WAIT_CONFIRM);
  }

  // 确认收货后
  public function confirm() {
    return $this->updateOrderStatus(self::ORDER_SUCCESS);
  }

  // 取消订单 待付款时
  public function cancel() {
    $res = $this->updateOrderStatus(self::ORDER_CANCEL);
    // 如果取消订单成功
    if ($res) {
      // 更新商品信息
      $this->goods->amount = $this->goods->amount + $this->num;
      $this->goods->sold = $this->goods->sold - $this->num;
      $this->goods->update();
    }
    return $res;
  }

  // 更新订单状态
  public function updateOrderStatus($v) {
    if (empty($v) || !is_numeric($v)) {
      return false;
    }
    $this->status = $v;
    try {
      $res = $this->update();
    } catch(\Exception $e) {
      return false;
    }
    return $res;
  }


  // 检测能否进行支付 即订单状态是否为待支付
  public function canPay() {
    if ($this->status == self::ORDER_WAIT_PAY) {
      return true;
    }
    return '该订单无法支付，可能已超过有效支付时间，建议重新拍下商品进行支付。';
  }

}
