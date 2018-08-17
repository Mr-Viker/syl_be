<?php
/**
 * 订单定时任务类
 */
namespace App\Console\Crontab;

use App\Models\Order as OrderModel;
use Illuminate\Support\Facades\Log;

class Order {

  // 检测待发货订单
  public static function checkWaitSendOrder() {
    Log::info('================== 触发检测待发货订单任务Crontab ====================');
    $orders = OrderModel::where('status', OrderModel::ORDER_WAIT_SEND)->pluck('id');
    Log::info("共有待发货订单: ".$orders->count());
    Log::info("待发货订单ID: ".json_encode($orders->toArray()));
    Log::info('================== 结束检测待发货订单任务 ===========================');
  }

}