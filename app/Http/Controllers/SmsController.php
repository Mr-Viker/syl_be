<?php
/**
 * 短信控制器
 */
namespace App\Http\Controllers;

use App\Models\User;
use App\Validators\UserValidator;
use Illuminate\Http\Request;

class SmsController extends Controller {

  // 发送
  public function send(Request $req) {
    $data = $req->only(['phone', 'type']);
    // 验证
    $valid = UserValidator::handle($data, 'sms');
    if (true !== $valid) {
      return ['code' => '01', 'msg' => $valid->first()];
    }
    $user = new User();
    $isExists = $user->isExists($data['phone']);
    // 如果是注册 则需要查看该手机号是否已注册
    if ($data['type'] == 'register' && $isExists) {
      return ['code' => '01', 'msg' => '手机号已注册'];
    }
    if ($data['type'] == 'forgetPassword' && !($isExists)) {
      return ['code' => '01', 'msg' => '手机号未注册'];
    }
    // send
    try {
      // 要保存的用户ID/IP 可不传
      $saveUserId = empty($req->userInfo) ? $req->getClientIp() : $req->userInfo->id;
      $res = app('Sms')::sendSms($data['phone'], $data['type'], $saveUserId);
      if ($res->Code == 'OK') {
        return ['code' => '00', 'msg' => '发送成功'];
      } else {
        return ['code' => '500', 'msg' => $res->Message];
      }
    } catch(\Exception $e) {
      return ['code' => '500', 'msg' => $e->getMessage()];
    }
  }

}