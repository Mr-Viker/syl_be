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

  // 收藏 用户和产品多对多的关系
  public function collects() {
    return $this->belongsToMany(Goods::class, 'collects', 'user_id', 'goods_id');
  }

  // 地址 用户和地址 一对多
  public function addresses() {
    return $this->hasMany(Address::class, 'user_id');
  }

  // 订单 一对多
  public function orders() {
    return $this->hasMany(Order::class, 'user_id');
  }

  // 订单 一对多
  public function pays() {
    return $this->hasMany(Pay::class, 'user_id');
  }

}
