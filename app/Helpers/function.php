<?php
/**
 * 公共方法
 */

use App\Models\lsConfig;


if (!function_exists('lsConfig')) {
  // 获取ls_configs表的数据
  function lsConfig($key) {
    return empty($key) ? lsConfig::getAll() : lsConfig::getConfig($key);
  }
}


if (!function_exists('formatPageData')) {
  // 格式化paginate得到的数据
  function formatPageData($res) {
    return $result = [
      'data' => $res['data'],      
      'curPage' => $res['current_page'],      
      'lastPage' => $res['last_page'],      
      'prePage' => $res['per_page'],     
      'total' => $res['total'],     
      'code' => '00',
      'msg' => '获取成功',
    ];
  }
}
