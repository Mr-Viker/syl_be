<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class MailController extends Controller implements ShouldQueue
{
  public function send()
    {
          // Mail::send()的返回值为空，所以可以其他方法进行判断
          Mail::raw("<strong>爱你哟IVIKER</strong>", function($message){
              $to = '1973601102@qq.com';
              $message ->to($to)->subject('邮件测试');
          });
          // 返回的一个错误数组，利用此可以判断是否发送成功
          dd(Mail::failures());
    }

}
