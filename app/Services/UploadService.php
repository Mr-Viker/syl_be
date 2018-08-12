<?php
/**
 * 上传服务
 */
namespace App\Services;

use Illuminate\Http\Request;

class UploadService {

  public $app;

  public function __construct($app) {
    $this->app = $app;
  }

  // 上传图片
  public function uploadImg(Request $req) {
    $files = $req->file();
    $urls = [];
    $allRight = true;
    foreach ($files as $key => $file) {
      if ($file->isValid()) {
        try {
          $ext = $file->getClientOriginalExtension();
          $distName = md5(uniqid()) . '.' . $ext;
          $res = $file->move(public_path('uploads'), $distName);
          $urls[$key] = $distName;
        } catch(\Exception $e) {
          return ['code' => '500', 'msg' => $e->getMessage()];
        }
      } else {
        $urls[$key] = $file->getErrorMessage();
        $allRight = false;
      }
    }
    return $allRight 
            ? ['code' => '00', 'data' => $urls, 'msg' => '上传成功'] 
            : ['code' => '500', 'data' => $urls, 'msg' => '上传失败'];
  }

}