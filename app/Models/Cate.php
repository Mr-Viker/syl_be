<?php

namespace App\Models;

use App\Models\Goods;
use Illuminate\Database\Eloquent\Model;

class Cate extends Model
{
  // 与产品关联
  public function goods() {
    return $this->hasMany(Goods::class, 'cate_id');
  }

  // public function getStatusAttribute($v) {
  //   return $v == 0 ? '正常' : '下架';
  // }


  // 获取所有分类 并返回id为key name为value的关联数组
  public static function getAll($key = 'id', $value = 'name', $valid = false) {
    $cates = $valid ? self::where('status', 0)->get() : self::all();
    $newCates = [];
    foreach ($cates as $v) {
        $newCates[$v[$key]] = $v[$value];
    }
    return $newCates;
  }

}
