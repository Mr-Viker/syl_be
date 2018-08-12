<?php
/**
 * 短信服务提供者
 */
namespace App\Providers;

use App\Services\SmsService;
use Illuminate\Support\ServiceProvider;

class SmsServiceProvider extends ServiceProvider {

  // 注册短信服务
  public function register() {
    $this->app->bind('Sms', function() {
      return new SmsService();
    });
  }

}