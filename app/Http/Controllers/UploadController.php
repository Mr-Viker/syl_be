<?php
/**
 * 上传
 */
namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UploadController extends Controller {

  // 上传图片
  public function upload(Request $req) {
    return app('Upload')->uploadImg($req);
  }

}