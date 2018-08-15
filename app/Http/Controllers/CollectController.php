<?php
/**
 * 收藏控制器
 */
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class CollectController extends Controller
{
  // 列表
  public function index(Request $req) {
    $data = $req->only(['page', 'prePage']);
    empty($data['page']) ? $data['page'] = 1 : '';
    empty($data['prePage']) ? $data['prePage'] = 10 : '';
    $user = User::find($req->userInfo->id);
    $res = $user->collects()->paginate($data['prePage'], ['*'], '', $data['page'])->toArray();
    // 因为是二维数组 所以要传址
    foreach ($res['data'] as &$value) {
      $value['isCollected'] = 1;
    }
    return formatPageData($res);
    // return ['code' => '00', 'data' => $res, 'msg' => '获取成功'];
  }

  // 收藏
  public function store(Request $req) {
    $data = $req->only(['goodsId']);
    if (empty($data['goodsId'])) {
      return ['code' => '01', 'msg' => '缺少商品ID'];
    }
    $data['userId'] = $req->userInfo->id;
    $user = User::find($data['userId']);
    try {
      $isExist = $user->collects()->find($data['goodsId']);
      if ($isExist) {
        return ['code' => '01', 'msg' => '已经收藏过啦~'];
      }
      $user->collects()->attach($data['goodsId']);
    } catch(\Exception $e) {
      return ['code' => '500', 'msg' => $e->getMessage()];
    }
    return ['code' => '00', 'msg' => '收藏成功'];
  }

  // 取消收藏
  public function destroy(Request $req) {
    $data = $req->only(['goodsId']);
    if (empty($data['goodsId'])) {
      return ['code' => '01', 'msg' => '缺少商品ID'];
    }
    $data['userId'] = $req->userInfo->id;
    $user = User::find($data['userId']);
    try {
      $isExist = $user->collects()->find($data['goodsId']);
      if (!$isExist) {
        return ['code' => '01', 'msg' => '已经取消收藏啦~'];
      }
      $user->collects()->detach($data['goodsId']);
    } catch(\Exception $e) {
      return ['code' => '500', 'msg' => $e->getMessage()];
    }
    return ['code' => '00', 'msg' => '取消收藏成功'];
  }
  
}
