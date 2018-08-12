<?php
/**
 * 解决跨域
 */
namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Response;

class CORS {

  public function handle($req, Closure $next) {
    header('Access-Control-Allow-Origin: *');
    // header("Access-Control-Allow-Credentials: true"); // 不能和上面的*同时设置
    header("Access-Control-Allow-Methods: OPTIONS, GET, POST, PUT, DELETE");
    // 允许前端在接口中传递的header字段
    header("Access-Control-Allow-Headers: Content-Type, Cache-Control, Authorization, x-requested-with, X-Requested_With, Access-Token, token");
    header("Access-Control-Expose-Headers: *");

    // 防止走了两次控制器方法
    if ($req->isMethod('OPTIONS')) {
      return Response::make(['code' => '200', 'msg' => 'OK']);
    }

    return $next($req);
  }

}