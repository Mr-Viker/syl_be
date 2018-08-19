<?php

namespace App\Admin\Controllers;

use App\Models\Pay;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;

class PayController extends Controller
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

            $content->header('支付管理');
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

            $content->header('支付管理');
            $content->description('编辑');

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

            $content->header('支付管理');
            $content->description('添加');

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
        return Admin::grid(Pay::class, function (Grid $grid) {
            $grid->model()->orderBy('id', 'desc');
            $grid->disableCreateButton();

            $grid->id('ID')->sortable();
            $grid->order_id('订单ID')->sortable();
            $grid->user('用户')->display(function($user) {
                // dd($this->user);
                return "<a href='" . url('admin/user?id='.$user['id']) . "' style='display: block; text-align:left;' title='点击查看'><img src='" . url('uploads') . '/' . $user['avatar'] . "' alt='' style='display:inline-block;width:60px;height:60px;border-radius:50%;'><span style='display: block;'>" . $user['username'] . "</span></a>";
            })->sortable();
            $grid->type('支付类型')->sortable();
            $grid->status('状态')->payStatus()->sortable();
            $grid->created_at('创建时间')->sortable();
            $grid->updated_at('更新时间')->sortable();
        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        return Admin::form(Pay::class, function (Form $form) {
            $status = Pay::getAllStatus();
            $form->display('id', 'ID');
            $form->display('order_id', '订单ID');
            $form->display('user_id', '用户ID');
            $form->display('type', '支付类型');
            $form->radio('status', '状态')->options($status);

            // $form->display('created_at', 'Created At');
            // $form->display('updated_at', 'Updated At');
        });
    }
}
