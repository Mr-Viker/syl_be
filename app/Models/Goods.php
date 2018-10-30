<?php

namespace App\Models;

use App\Models\Cate;
use Illuminate\Database\Eloquent\Model;

class Goods extends Model
{
  // public function getStatusAttribute($v) {
  //   return $v == 0 ? '在售' : '下架';
  // }

  // 与所属分类关联
  public function cate() {
    return $this->belongsTo(Cate::class, 'cate_id');
  }

  // 收藏 
  public function collects() {
    return $this->belongsToMany(User::class, 'collects', 'goods_id', 'user_id');
  }

  // 详情图片
  // public function goodsImgs() {
  //   return $this->hasMany(GoodsImg::class, 'goods_id');
  // }

  public function setImgsAttribute($v) {
    if (is_array($v)) {
      $this->attributes['imgs'] = json_encode($v);
    }
  }

  public function getImgsAttribute($v) {
    return json_decode($v, true);
  }

}
