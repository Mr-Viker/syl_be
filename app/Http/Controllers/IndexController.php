<?php
/**
 * 首页控制器
 */
namespace App\Http\Controllers;

use App\Models\BigPic;
use App\Models\Carousel;
use Illuminate\Support\Facades\Response;

class IndexController extends Controller {

  // 获取首页轮播图
  public function banner() {
    $data = Carousel::where('status', 0)->orderBy('created_at', 'desc')->get();
    return ['code' => '00', 'data' => $data, 'msg' => '获取成功'];
  }


  // 获取大图专区列表
  public function bigPic() {
    $data = BigPic::where('status', 0)->get();
    return ['code' => '00', 'data' => $data, 'msg' => '获取成功'];
  }





  // public function __call($method, $args) {
  //   $view = 'index.' . $method;
  //   if (view()->exists($view)) {
  //     return view($view);
  //   }
  //   return Response::make('404 NOT FOUND', 404);
  // }
  
}