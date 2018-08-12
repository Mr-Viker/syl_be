<?php
/**
 * 公共方法
 */

use App\Models\lsConfig;


if (!function_exists('lsConfig')) {
  function lsConfig($key) {
    return empty($key) ? lsConfig::getAll() : lsConfig::getConfig($key);
  }
}