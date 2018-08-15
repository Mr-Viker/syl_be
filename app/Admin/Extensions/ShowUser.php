<?php
/**
 * 在列表中显示头像昵称
 */
namespace App\Admin\Extensions;

use Encore\Admin\Grid\Displayers\AbstractDisplayer;

class ShowUser extends AbstractDisplayer {
  public function display() {
    // return 'hihihi';
    return "<a href='" . url('admin/user?id='.$user['id']) . "' style='display: block; text-align:center;' title='点击查看'><img src='" . url('uploads') . '/' . $user['avatar'] . "' alt='' style='display:inline-block;width:60px;height:60px;border-radius:50%;'><span style='display: block; text-align: center;'>" . $user['username'] . "</span></a>";
  }
}