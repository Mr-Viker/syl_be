<?php
/**
 * 支付模型
 */
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pay extends Model
{
  private static $status = [
    0 => '未支付',
    1 => '成功',
    2 => '失败',
  ];

  // 支付-订单 一对一
  public function order() {
    return $this->belongsTo(Order::class, 'order_id');
  }

  // 支付-用户 多对一
  public function user() {
    return $this->belongsTo(User::class, 'user_id');
  }

  // 检测是否是已支付成功的订单
  public function checkSuccess() {
    return $this->status == 1;
  }

  // 更新状态为支付成功
  public function updateSuccessStatus() {
    $this->status = 1;
    return $this;
  }

  // 更新状态为支付失败
  public function updateFailStatus() {
    $this->status = 2;
    return $this;
  }

  // 获取所有状态
  public static function getAllStatus() {
    return self::$status;
  }

}
