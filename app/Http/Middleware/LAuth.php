<?php

namespace App\Http\Middleware;

use Closure;
use Tymon\JWTAuth\Exceptions\JWTException;

class LAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (empty($request->header('token'))) {
            return response()->json(['code' => '500', 'msg' => '用户未登录']);
        }
        try {
            $request->userInfo = \JWTAuth::parseToken("bearer", "token")->authenticate();
            if (!$request->userInfo) {
                return response()->json(['code' => '500', 'msg' => '用户未登录']);
            }
        } catch (JWTException $e) {
            return response()->json(['code' => '500', 'msg' => $e->getMessage()]);
        }

        return $next($request);


        // if (!(\Session::get('user'))) {
        //     return redirect('login');
        // }
        // return $next($request);
    }
}
