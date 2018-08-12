<?php

namespace App\Admin\Controllers;

use App\Models\Sms;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;

class SmsController extends Controller
{
    use ModelForm;

    /**
     * Index interface.
     *
     * @return Content
     */
    public function index()
    {
        return Admin::content(function (Content $content) {

            $content->header('短信管理');
            $content->description('列表');

            $content->body($this->grid());
        });
    }

    /**
     * Edit interface.
     *
     * @param $id
     * @return Content
     */
    public function edit($id)
    {
        return Admin::content(function (Content $content) use ($id) {

            $content->header('header');
            $content->description('description');

            $content->body($this->form()->edit($id));
        });
    }

    /**
     * Create interface.
     *
     * @return Content
     */
    public function create()
    {
        return Admin::content(function (Content $content) {

            $content->header('header');
            $content->description('description');

            $content->body($this->form());
        });
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Admin::grid(Sms::class, function (Grid $grid) {
            $grid->disableCreateButton();
            $grid->disableRowSelector();
            $grid->disableActions();

            $grid->id('ID')->sortable();
            $grid->user_id('用户ID/IP')->display(function($v) {
                if (is_numeric($v)) {
                    return "<a href='" + url('admin/user') + '?id=' + $v + "' title='点击查看' class='label label-success'>" + $v + "</a>";
                } else {
                    return "<span class='label label-success'>{$v}</span>";
                }
            });
            $grid->phone('手机号')->sortable();
            $grid->code('验证码');
            $grid->type('类型')->display(function($v) {
                $types = ['register' => '注册', 'change_password' => '修改密码'];
                return "<span class='label label-success'>{$types[$v]}</span>";
            })->sortable();
            $grid->result('返回结果')->display(function($v) {
                return "<span style='word-break: break-all; display:inline-block; max-width:300px;'>{$v}</span>";
            });
            $grid->status('状态')->display(function($v) {
                if ($v == 0) {
                    return "<span class='label label-success'>未使用</span>";
                } else {
                    return "<span class='label label-danger'>已使用</span>";
                }
            });
            $grid->created_at('发送时间')->sortable();
            // $grid->updated_at();

            $grid->filter(function($filter) {
                $filter->like('user_id', '用户ID/IP');
                $filter->like('phone', '手机号');
                $filter->in('type', '类型')->multipleSelect(['register' => '注册', 'change_password' => '修改密码']);
                $filter->in('status', '状态')->multipleSelect(['0' => '未使用', '1' => '已使用']);
            });
        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        return Admin::form(Sms::class, function (Form $form) {

            $form->display('id', 'ID');

            $form->display('created_at', 'Created At');
            $form->display('updated_at', 'Updated At');
        });
    }
}
