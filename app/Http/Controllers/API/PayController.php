<?php
/**
 * 支付处理
 */
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Pay;
use App\Validators\PayValidator;
use Illuminate\Http\Request;

class PayController extends Controller
{
  // 支付
  public function pay(Request $req) {
    $data = $req->only(['orderId', 'type']);
    // 验证
    $valid = PayValidator::handle($data, 'pay');
    if ($valid !== true) {
      return ['code' => '01', 'msg' => $valid->first()];
    }
    // 检测订单状态
    $canPay = Order::find($data['orderId'])->canPay();
    if ($canPay !== true) {
      return ['code' => '01', 'msg' => $canPay];
    }
    // 检测是否有该支付记录了 防止重复提交
    $pay = Pay::where('order_id', $data['orderId'])->first();
    if ($pay) {
      if ($pay->checkSuccess()) {
        return ['code' => '01', 'msg' => '已经支付过啦~'];
      } else {
        // 如果是已经存在的未成功的支付记录 则更新一些其他信息 然后调起第三方支付
        $pay->type = $data['type'];
        $pay->update();
      }
    } else {
      // 如果没有支付过 则创建一条支付记录 然后调起第三方
      $pay = new Pay();
      $pay->order_id = $data['orderId'];
      $pay->type = $data['type'];
      $pay->user_id = $req->userInfo->id;
      $pay->save();
    }
    // 调起第三方支付 会传回调地址(前端支付结果页地址？不是)
    sleep(2);
    return redirect("api/pay/callback?id={$pay->id}&code=OK");
  }

  // 支付回调地址 第三方调用的
  public function callback(Request $req) {
    $data = $req->only(['id', 'code']);
    $pay = Pay::find($data['id']);
    // 更新支付记录和订单记录
    if (strtoupper($data['code']) === 'OK') {
      $pay->updateSuccessStatus()->update();
      $pay->order->updatePaySuccessStatus();
      return ['code' => '00', 'data' => ['orderId' => $pay->order->id], 'msg' => '支付成功'];
    }
    $pay->updateFailStatus()->update();
    return ['code' => '01', 'data' => ['orderId' => $pay->order->id], 'msg' => '支付失败'];
  }

}
