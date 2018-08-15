<?php

namespace App\Admin\Controllers;

use App\Admin\Extensions\ShowUser;
use App\Http\Controllers\Controller;
use App\Models\Order;
use Encore\Admin\Controllers\ModelForm;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;

class OrderController extends Controller
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

            $content->header('订单管理');
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

            $content->header('订单管理');
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

            $content->header('订单管理');
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
        return Admin::grid(Order::class, function (Grid $grid) {
            $grid->model()->orderBy('updated_at', 'desc');
            $grid->disableCreation();
            $grid->disableRowSelector();

            $grid->id('ID')->sortable();
            $grid->user('用户')->display(function($user) {
                return "<a href='" . url('admin/user?id='.$user['id']) . "' style='display: block; text-align:center;' title='点击查看'><img src='" . url('uploads') . '/' . $user['avatar'] . "' alt='' style='display:inline-block;width:60px;height:60px;border-radius:50%;'><span style='display: block; text-align: center;'>" . $user['username'] . "</span></a>";
            });
            $grid->goods('商品')->display(function($goods) {
                return "<a href='". url('admin/goods?id='.$goods['id']) ."' title='点击查看' style='display:inline-block;max-width: 200px;text-overflow: ellipsis;overflow: hidden;white-space: nowrap;'><img src='". url('upload') . '/' . $goods['thumb'] ."' alt='' style='display:inline-block;max-width: 200px;max-height: 80px;height: auto;'>" . $goods['title']. "</a>";
            });
            $grid->price('单价')->sortable();
            $grid->num('数量')->sortable();
            $grid->total('总价')->sortable();
            $grid->realname('收货人');
            $grid->phone('收货电话');
            $grid->address('收货地址');
            $grid->status('状态')->sortable()->orderStatus();
            $grid->created_at('创建时间');
            $grid->updated_at('更新时间');

            $grid->filter(function ($filter) {
                $filter->like('realname', '收货人');
                $filter->like('phone', '收货电话');
                // $filter->where(function ($query) {
                //     $input = $this->input;
                //     dd($this->input);
                //     $query->where('nickname', 'like', "%{$input}%")->orWhere('openid', 'like', "%{$input}%")->orWhere('phone', 'like', "%{$input}%");
                // }, '昵称/手机号/openid');
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
        return Admin::form(Order::class, function (Form $form) {
            $status = $form->model()->getAllStatus();
            // dd($status);

            $form->display('id', 'ID');
            $form->display('user_id', '用户ID')->attribute('disabled', true);
            $form->display('goods_id', '商品ID')->attribute('disabled', true);
            $form->currency('price', '单价')->symbol('￥')->rules('required');
            $form->number('num', '数量')->rules('required');
            $form->currency('total', '总价')->symbol('￥')->rules('required');
            $form->text('realname', '收货人')->rules('required');
            $form->mobile('phone', '收货电话')->rules('required');
            $form->text('address', '收货地址')->rules('required');
            $form->radio('status', '状态')->rules('required')->options($status);
            $form->display('created_at', 'Created At');
            $form->display('updated_at', 'Updated At');
        });
    }

}
