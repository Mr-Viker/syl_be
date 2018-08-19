<?php
/**
 * 用户控制器
 */
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Notifications\InvoicePaid;
use App\Validators\UserValidator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Notification;
use Tymon\JWTAuth\Exceptions\JWTException;

class UserController extends Controller
{
  // 注册
  public function register(Request $req) {
    $data = $req->only(['username', 'phone', 'password', 'confirmPassword', 'smsCode', 'avatar']);
    // dd($data);
    // 验证
    $valid = UserValidator::handle($data, 'register');
    if (true !== $valid) {
      return ['code' => '01', 'msg' => $valid->first()];
    }
    // 检测手机号是否已注册
    $user = new User();
    if ($user->isExists($data['phone'])) {
      return ['code' => '01', 'msg' => '手机号已注册'];
    }
    // 检测短信验证码
    if (!(app('Sms')::check($data['phone'], $data['smsCode'], 'register'))) {
      return ['code' => '01', 'msg' => '短信验证码错误'];
    }
    
    // 入库
    $data['password'] = \Hash::make($data['password']);
 
    $user->username = $data['username'];
    $user->phone = $data['phone'];
    $user->password = $data['password'];
    empty($data['avatar']) ?: $user->avatar = $data['avatar'];
    try {
      $res = $user->save();
      // 验证用户(User表) 如果成功返回一个token
      if (!$token = \JWTAuth::fromUser($user)) {
        return ['code' => '01', 'msg' => '生成token失败'];
      }
    } catch(\Exception $e) {
      return ['code' => '500', 'msg' => $e->getMessage()];
    }

    $user->token = $token;
    $userInfo = $user->toArray();
    $userInfo['status'] = $user->getAttribute('status');
    // 保存成功则生成一个token并将token和用户信息返回给前端
    return ['code' => '00', 'data' => $userInfo, 'msg' => '注册成功'];
  }


  // 登录
  public function login(Request $req) {
    $data = $req->only(['phone', 'password']);
    // 验证
    $valid = UserValidator::handle($data, 'login');
    if (true !== $valid) {
      return ['code' => '01', 'msg' => $valid->first()];
    }
    // 检测手机号是否已注册
    $user = User::where('phone', $data['phone'])->first();
    if (!$user) {
      return ['code' => '01', 'msg' => '手机号未注册'];
    }
    // 检测密码
    try {
      // if ($data['password'] !== \Crypt::decrypt($user->password)) {
      if (!(\Hash::check($data['password'], $user->password))) {
        return ['code' => '01', 'msg' => '密码错误'];
      }
    } catch(\Exception $e) {
      return ['code' => '500', 'msg' => $e->getMessage()];
    }

    if ($user->status != 0) {
      return ['code' => '500', 'msg' => '账户已被冻结'];
    }

    try {
      // 验证用户(User表) 如果成功返回一个token
      if (!$token = \JWTAuth::fromUser($user)) {
        return ['code' => '01', 'msg' => '手机号或密码错误'];
      }
    } catch (JWTException $e) {
      return ['code' => '500', 'msg' => $e->getMessage()];
    }

    // Notification::send($user, new InvoicePaid($user));
    // Cache::store('file')->put('user', $user);

    $user->token = $token;
    $userInfo = $user->toArray();
    $userInfo['status'] = $user->getOriginal('status');
    // 保存用户信息到session
    // \Session::put('user', $userInfo);
    return ['code' => '00', 'data' => $userInfo, 'msg' => '登录成功'];
  }


  //  获取用户信息
  public function info(Request $req) {
    // $user = \Session::get('user');

    try {
      $id = $req->userInfo->id;
      $user = User::find($id);
      // 获取用户每种状态的订单数
      $user->waitPay = $user->orders()->where('status', 0)->count();
      $user->waitSend = $user->orders()->where('status', 1)->count();
      $user->waitConfirm = $user->orders()->where('status', 2)->count();

      $userInfo = $user->toArray();
      $userInfo['status'] = $user->getOriginal('status');
    } catch(\Exception $e) {
      return ['code' => '500', 'msg' => $e->getMessage()];
    }
    return ['code' => '00', 'data' => $userInfo, 'msg' => '获取成功'];
  }


  //  检测用户登录
  public function logout() {
    // \Session::flush();
    \JWTAuth::invalidate();
    return ['code' => '00', 'msg' => '退出登录成功'];
  }


  // 更新用户头像
  public function uploadAvatar(Request $req) {
    $res = app('Upload')->uploadImg($req);
    if ($res['code'] === '00') {
      try {
        $id = $req->userInfo->id;
        $user = User::find($id);
        $user->avatar = $res['data']['avatar'];
        $user->update();
        return ['code' => '00', 'data' => $user->avatar, 'msg' => '更新成功'];
      } catch(\Exception $e) {
        return ['code' => '500', 'msg' => $e->getMessage()];
      }
    }
    return ['code' => '500', 'msg' => '上传失败'];
  }


  // 更新用户信息
  public function update(Request $req) {
    $data = $req->all();
    if (empty($data['type'])) {
      return ['code' => '01', 'msg' => '缺少更新类型[type]'];
    }
    $user = User::find($req->userInfo->id);
    switch ($data['type']) {
      // 修改昵称
      case 'username':
        if (empty($data['username'])) {
          return ['code' => '01', 'msg' => '昵称不能为空'];
        }
        $user->username = $data['username'];
        break;

      // 修改密码
      case 'password':
        $valid = UserValidator::handle($data, 'changePassword', $req);
        if (true !== $valid) {
          return ['code' => '01', 'msg' => $valid->first()];
        }
        $user->password = \Hash::make($data['password']);
        break;

      // 修改自动播放设置
      case 'autoplay':
        if (!isset($data['autoplay'])) {
          return ['code' => '01', 'msg' => '参数不能为空'];
        }
        $user->autoplay = $data['autoplay'];
        break;
    }
    // 入库
    try {
      $res = $user->update();
    } catch(\Exception $e) {
      return ['code' => '500', 'msg' => $e->getMessage()];
    }
    return ['code' => '00', 'msg' => '更新成功'];
  }


  // 忘记密码
  public function forgetPassword(Request $req) {
    $data = $req->only(['phone', 'smsCode', 'password', 'confirmPassword']);
    // 验证
    $valid = UserValidator::handle($data, 'forgetPassword');
    if ($valid !== true) {
      return ['code' => '01', 'msg' => $valid->first()];
    }
    // 检测手机号是否已注册
    $user = User::where('phone', $data['phone'])->first();
    if (!$user) {
      return ['code' => '01', '手机号未注册'];
    }
    // 检测短信验证码
    if (!(app('Sms')::check($data['phone'], $data['smsCode'], 'forgetPassword'))) {
      return ['code' => '01', 'msg' => '验证码错误'];
    }
    // 更新
    try {
      $user->password = \Hash::make($data['password']);
      $user->update();
    } catch(\Exception $e) {
      return ['code' => '500', 'msg' => $e->getMessage()];
    }
    return ['code' => '00', 'msg' => '更新成功'];
  }





}
