<?php
/**
 * 订单控制器
 */
namespace App\Http\Controllers\API;

use App\Events\OrderUpdate;
use App\Http\Controllers\Controller;
use App\Jobs\Order as OrderJob;
use App\Models\Address;
use App\Models\Goods;
use App\Models\Order;
use App\Models\User;
use App\Validators\OrderValidator;
use Illuminate\Http\Request;

class OrderController extends Controller
{
  // 列表
  public function index(Request $req) {
    $data = $req->only(['type', 'page', 'prePage']);
    empty($data['page']) ? $data['page'] = 1 : '';
    empty($data['prePage']) ? $data['prePage'] = 10 : '';
    // 查询
    try {
      $user = User::find($req->userInfo->id);
      // 全部订单
      if (!isset($data['type']) ||$data['type'] == '-1') {
        $query = $user->orders();
      } else {
        // 某状态订单
        $query = $user->orders()->where('status', $data['type']);
      }
      $res = $query->orderBy('id', 'desc')->with('goods')->paginate($data['prePage'], ['*'], '', $data['page'])->toArray();
    } catch (\Exception $e) {
      return ['code' => '00', 'msg' => $e->getMessage()];
    }
    return formatPageData($res);
  }


  // 下单
  public function store(Request $req) {
    $data = $req->only(['goodsId', 'price', 'num', 'addressId']);
    // 验证
    $valid = OrderValidator::handle($data, 'store');
    if ($valid !== true) {
      return ['code' => '01', 'msg' => $valid->first()];
    }
    $data['userId'] = $req->userInfo->id;
    $data['total'] = floatval($data['price']) * intval($data['num']);
    $address = Address::find($data['addressId']);
    $data['realname'] = $address->realname;
    $data['phone'] = $address->phone;
    $data['address'] = $address->province . $address->city . $address->county . $address->area;
    // 判断购买数量是否大于库存
    $goods = Goods::find($data['goodsId']);
    if ($data['num'] > $goods->amount) {
      return ['code' => '01', 'msg' => '购买数量不能大于库存'];
    }
    // 入库
    try {
      // 开启事务，如果下单成功 则更新库存和已售等商品信息
      \DB::beginTransaction();
      $order = new Order();
      $order->user_id = $data['userId'];
      $order->goods_id = $data['goodsId'];
      $order->realname = $data['realname'];
      $order->phone = $data['phone'];
      $order->address = $data['address'];
      $order->price = $data['price'];
      $order->num = $data['num'];
      $order->total = $data['total'];
      $order->save();
      // 更新商品信息
      $goods->amount = $goods->amount - $data['num'];
      $goods->sold = $goods->sold + $data['num'];
      // 防止并发
      if ($goods->amount < 0) {
        throw new \Exception('库存不足，购买失败');
      }
      $goods->update();
      \DB::commit();
      // // 发出下单成功事件
      // \Event::fire(new OrderUpdate($order));
      // 生成一个检测订单队列，如果三十分钟还未支付成功，则取消订单
      OrderJob::dispatch($order)->delay(now()->addMinutes(1));

    } catch(\Exception $e) {
      \DB::rollBack();
      return ['code' => '500', 'msg' => $e->getMessage()];
    }
    return ['code' => '00', 'data' => ['id' => $order->id], 'msg' => '下单成功'];
  }
  

  // 取消订单 在待付款时可操作
  public function cancel(Request $req) {
    $data = $req->only(['id']);
    // 验证
    $valid = OrderValidator::handle($data, 'cancel');
    if ($valid !== true) {
      return ['code' => '01', 'msg' => $valid->first()];
    }
    $order = Order::find($data['id']);
    if (!$order) {
      return ['code' => '01', 'msg' => '订单不存在'];
    }
    // 取消 需要将商品数量回滚
    try {
      \DB::beginTransaction();
      $res = $order->cancel();
      // 更新商品信息
      // $goods = $order->goods;
      // $goods->amount = $goods->amount + $order->num;
      // $goods->sold = $goods->sold - $order->num;
      // $goods->update();
      \DB::commit();
      if (!$res) {
        \DB::rollBack();
        return ['code' => '01', 'msg' => '取消订单失败'];
      }
    } catch(\Exception $e) {
      return ['code' => '500', 'msg' => $e->getMessage()];
    }
    return ['code' => '00', 'msg' => '取消成功'];
  }


  // 检测订单 前端支付结果页轮询
  public function check(Request $req) {
    $data = $req->only(['id']);
    // 验证
    $valid = OrderValidator::handle($data, 'check');
    if ($valid !== true) {
      return ['code' => '01', 'msg' => $valid->first()];
    }
    $order = Order::find($data['id']);
    if (!$order) {
      return ['code' => '01', 'msg' => '订单不存在'];
    }
    return ['code' => '00', 'data' => $order, 'msg' => '获取成功'];
  }

  // 确认收货
  public function confirm(Request $req) {
    $data = $req->only(['id']);
    // 验证
    $valid = OrderValidator::handle($data, 'confirm');
    if ($valid !== true) {
      return ['code' => '01', 'msg' => $valid->first()];
    }
    $order = Order::find($data['id']);
    if (!$order) {
      return ['code' => '01', 'msg' => '订单不存在'];
    }
    // 更新
    try {
      $res = $order->confirm();
      if (!$res) {
        return ['code' => '01', 'msg' => '确认收货失败'];
      }
    } catch(\Exception $e) {
      return ['code' => '500', 'msg' => $e->getMessage()];
    }
    return ['code' => '00', 'msg' => '确认收货成功'];
  }







}
