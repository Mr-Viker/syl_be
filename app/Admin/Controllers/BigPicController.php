<?php

namespace App\Admin\Controllers;

use App\Models\BigPic;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;

class BigPicController extends Controller
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

            $content->header('大图专区管理');
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

            $content->header('大图专区管理');
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

            $content->header('大图专区管理');
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
        return Admin::grid(BigPic::class, function (Grid $grid) {

            $grid->id('ID')->sortable();
            $grid->title('标题');
            $grid->img('图片')->image();
            $grid->link('链接')->display(function($v) {
                return "<a href='{$v}'>{$v}</a>";
            });
            $grid->status('状态')->sortable();
            $grid->created_at('创建时间')->sortable();        
        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        return Admin::form(BigPic::class, function (Form $form) {

            $form->display('id', 'ID');
            $form->text('title', '标题');
            $form->image('img', '图片');
            $form->text('link', '链接');
            $form->radio('status', '状态')->options(['0' => '显示', '1' => '不显示'])->default('0');
            // $form->display('created_at', 'Created At');
            // $form->display('updated_at', 'Updated At');
        });
    }
}
