<?php

use App\Models\Order;
use App\Models\Pay;

/**
 * Laravel-admin - admin builder based on Laravel.
 * @author z-song <https://github.com/z-song>
 *
 * Bootstraper for Admin.
 *
 * Here you can remove builtin form field:
 * Encore\Admin\Form::forget(['map', 'editor']);
 *
 * Or extend custom form field:
 * Encore\Admin\Form::extend('php', PHPEditor::class);
 *
 * Or require js and css assets:
 * Admin::css('/packages/prettydocs/css/styles.css');
 * Admin::js('/packages/prettydocs/js/main.js');
 *
 */

// Encore\Admin\Form::forget(['map', 'editor']);


Admin::js('/static/admin/js/admin.js');



Encore\Admin\Grid\Column::extend('orderStatus', function ($value) {
  $status = Order::getAllStatus();
  switch ($value) {
    // 橙
    case 0:
        return "<span class='label label-warning'>{$status[$value]}</span>";
        break;
    // 红
    case 1:
        return "<span class='label label-danger'>{$status[$value]}</span>";
        break;
    // 蓝
    case 2:
        return "<span class='label label-info'>{$status[$value]}</span>";
        break;
    // 绿
    case 3:
        return "<span class='label label-success'>{$status[$value]}</span>";
        break;
    // 灰
    default:
        return "<span class='label label-default'>{$status[$value]}</span>";
        break;
  }
});

Encore\Admin\Grid\Column::extend('payStatus', function ($value) {
  $status = Pay::getAllStatus();
  switch ($value) {
    // 待支付
    case 0:
        return "<span class='label label-warning'>{$status[$value]}</span>";
        break;
    // 失败
    case 1:
        return "<span class='label label-success'>{$status[$value]}</span>";
        break;
    // 成功
    case 2:
        return "<span class='label label-danger'>{$status[$value]}</span>";
        break;
    // 默认
    default:
        return "<span class='label label-warning'>{$status[$value]}</span>";
        break;
  }
});

























