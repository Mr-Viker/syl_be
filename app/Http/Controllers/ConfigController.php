<?php

namespace App\Http\Controllers;

use App\Models\lsConfig;
use Illuminate\Http\Request;

class ConfigController extends Controller
{
  public function list() {
    $configs = lsConfig::getAll();
    $data = [
      'system_name' => $configs['system_name'],
    ];

    return ['code' => '00', 'data' => $data, 'msg' => '获取成功'];
  }
}
