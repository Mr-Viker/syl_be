<?php
/**
 * 产品控制器
 */
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Cate;
use App\Models\Goods;
use App\Models\User;
use Illuminate\Http\Request;

class GoodsController extends Controller
{
  // 获取分类列表
  public function cate() {
    $data = Cate::where('status', 0)->get();
    return ['code' => '00', 'data' => $data, 'msg' => '获取分类列表成功'];
  }
  

  // 获取产品列表
  public function list(Request $req) {
    $data = $req->only(['page', 'prePage', 'cateId']);
    // 验证
    empty($data['page']) ? $data['page'] = 1 : '';
    empty($data['prePage']) ? $data['prePage'] = 10 : '';

    $query = Goods::where('status', 0);
    empty($data['cateId']) ?: $query = $query->where('cate_id', $data['cateId']);
    $res = $query->paginate($data['prePage'], ['*'], '', $data['page'])->toArray();
    return formatPageData($res);
  }


  // 商品详情
  public function detail(Request $req) {
    $data = $req->only(['id']);
    // 验证
    if (empty($data['id'])) {
      return ['code' => '01', 'msg' => '缺少商品ID'];
    }
    // 查询
    $goods = Goods::where($data)->first();
    if ($goods->status != 0) {
      return ['code' => '01', 'msg' => '商品已下架'];
    }
    // 检测用户是否已收藏该商品
    if (empty($req->userInfo)) {
      $goods->isCollected = 0;
    } else {
      $goods->isCollected = $goods->collects()->find($req->userInfo->id) ? 1 : 0;
    }
    return ['code' => '00', 'data' => $goods, 'msg' => '获取成功'];
  }











}
