<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class lsConfig extends Model
{
    protected $table = 'ls_configs';


    // 获取格式化成关联数组后的配置
    public static function getAll() {
      $configs = self::get()->toArray();
      $distConfigs = [];
      foreach ($configs as $v) {
        $distConfigs[$v['key']] = $v['value'];
      }
      return $distConfigs;
    }

    // 获取某个key对应的value
    public static function getConfig($key) {
      $config = lsConfig::where('key', $key)->first();
      return isset($config) ? $config->value : null;
    }

}
