<?php
/**
 * 上传服务
 */
namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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


  // 上传文件
  public function uploadFile($file) {
    if(is_object($file) && get_class($file) == 'Illuminate\Http\UploadedFile'){
      $ext = $file->getClientOriginalExtension(); //文件拓展名
      $fileName = date('YmdHis') . md5(uniqid()) . ".{$ext}"; //新文件名
      $realPath = $file->getRealPath(); //临时文件的绝对路径

      $bool = Storage::disk('admin')->put($fileName, file_get_contents($realPath));
      if($bool){
        // return url('uploads'). '/' . $fileName;
        return $fileName;
      }
      return '';
    }
  }

}