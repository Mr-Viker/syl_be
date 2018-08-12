<?php
/**
 * 上传服务提供者
 */
namespace App\Providers;

use App\Services\UploadService;
use Illuminate\Support\ServiceProvider;

class UploadServiceProvider extends ServiceProvider {

  // 注册上传服务
  public function register() {
    $this->app->bind('Upload', function($app) {
      return new UploadService($app);
    });
  }

}