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

}
