<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
  protected $table = 'users';
  protected $hidden = ['password'];


  // 检测手机号是否已注册
  public function isExists($phone) {
    return $this->where('phone', $phone)->count() > 0;
  }

  // 属性获取器
  // public function getStatusAttribute($v) {
  //   return $v == 0 ? '正常' : '冻结';
  // }

}
