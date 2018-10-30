<?php

namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Cate;
use App\Models\Goods;
use Encore\Admin\Controllers\ModelForm;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;

class GoodsController extends Controller
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

            $content->header('产品管理');
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

            $content->header('产品管理');
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

            $content->header('产品管理');
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
        return Admin::grid(Goods::class, function (Grid $grid) {
            $grid->model()->orderBy('id', 'desc');

            $grid->id('ID')->sortable();
            $grid->cate()->name('分类')->sortable();
            $grid->title('名称')->sortable()->display(function($v) {
                return "<div style='width:200px; overflow:hidden;text-overflow:ellipsis;white-space:nowrap;'>{$v}</div>";
            });
            $grid->desc('介绍')->display(function($v) {
                return "<div style='max-width:200px; height:auto; max-height:100px; overflow:hidden;text-overflow:ellipsis;white-space:nowrap;'>{$v}</div>";
            });
            $grid->price('价格')->sortable();
            $grid->amount('库存')->sortable();
            $grid->sold('已售')->sortable();
            $grid->thumb('缩略图')->image('', 100, 100);
            $grid->status('状态')->display(function($v) {
                if ($v == 0) {
                    return "<span class='label label-success'>正常</span>";
                } else {
                    return "<span class='label label-danger'>下架</span>";
                }
            })->sortable();
            $grid->created_at('创建时间');
            // $grid->updated_at();
            $grid->filter(function($filter) {
                $cates = Cate::getAll();
                $filter->in('cate_id', '所属分类')->multipleSelect($cates);
                $filter->like('title', '名称');
                $filter->in('status', '状态')->multipleSelect(['0' => '正常', '1' => '下架']);
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
        return Admin::form(Goods::class, function (Form $form) {
            $cates = Cate::getAll('id', 'name', true);
            $form->display('id', 'ID');
            $form->select('cate_id', '分类')->options($cates)->rules('required');
            $form->text('title', '名称')->rules('required');
            $form->image('thumb', '缩略图')->uniqueName()->removable();
            $form->number('price', '价格')->rules('required');
            $form->number('freight', '运费')->default(0.00);
            $form->number('amount', '库存')->rules('required');
            $form->number('sold', '已售')->default(0)->rules('required');
            $form->radio('status', '状态')->options(['0' => '正常', '1' => '下架']);
            $form->multipleImage('imgs', '详情轮播图')->uniqueName()->removable();
            $form->editor('desc', '商品介绍');
            // $form->display('created_at', 'Created At');
            // $form->display('updated_at', 'Updated At');
        });
    }
}
