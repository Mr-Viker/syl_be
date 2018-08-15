<?php

namespace App\Http\Controllers;

use App\Models\lsConfig;
use Illuminate\Http\Request;

class ConfigController extends Controller
{
  public function list() {
    $configs = lsConfig::getAll();
    $data['system_name'] = isset($configs['system_name']) ? $configs['system_name'] : '';
    $data['qrcode'] = isset($configs['qrcode']) ? $configs['qrcode'] : '';
    $data['tel'] = isset($configs['tel']) ? $configs['tel'] : '';

    return ['code' => '00', 'data' => $data, 'msg' => '获取成功'];
  }
}
