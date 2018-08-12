<?php

namespace App\Admin\Controllers;

use App\Models\Cate;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;

class CateController extends Controller
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

            $content->header('分类管理');
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

            $content->header('分类管理');
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

            $content->header('分类管理');
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
        return Admin::grid(Cate::class, function (Grid $grid) {

            $grid->id('ID')->sortable();
            $grid->name('名称');
            $grid->icon('图标')->image('', 50, 50);
            $grid->status('状态')->display(function($v) {
                if ($v == 0) {
                    return "<span class='label label-success'>正常</span>";
                } else {
                    return "<span class='label label-danger'>下架</span>";
                }
            })->sortable();
            $grid->created_at('创建时间');
            // $grid->updated_at('更新时间');
        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        return Admin::form(Cate::class, function (Form $form) {

            $form->display('id', 'ID');
            $form->text('name', '名称')->rules('required');
            $form->image('icon', '图标')->uniqueName()->removable();
            $form->radio('status', '状态')->options(['0' => '正常', '1' => '下架']);
            // $form->display('created_at', 'Created At');
            // $form->display('updated_at', 'Updated At');
        });
    }
}
