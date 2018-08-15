<?php

namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use App\Models\lsConfig;
use Encore\Admin\Controllers\ModelForm;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Widgets\Collapse;
use Encore\Admin\Widgets\Table;
use Illuminate\Http\Request;

class ConfigController extends Controller
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

            $content->header('系统设置');
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

            $content->header('系统设置');
            $content->description('编辑');

            $content->body($this->form());
        });
    }


    /**
     * 保存
     *
     * @return Content
     */
    public function store(Request $req)
    {
        $data = $req->all();
        // dd(is_object($data['qrcode']), is_file($data['qrcode']), get_class($data['qrcode']));
        // dd($data);
        foreach ($data as $key => $value) {
            // 如果是laravel内置数据则不保存至数据库
            if ($key == '_token' || $key == '_previous_') {
                continue;
            }
            // 如果是图片 则需要保存
            if(is_object($value) && get_class($value) == 'Illuminate\Http\UploadedFile'){
                $value = app('Upload')->uploadFile($value);
            }
            // 如果是数组则序列化保存
            $value = is_array($value) ? serialize($value) : $value;
            // 获取对应的数据库记录 如果没有则新建
            $config = lsConfig::where('key', $key)->first();
            if (!$config) {
                $config = new lsConfig();
                $config->key = $key;
            }
            $config->value = $value;
            $config->save();
            unset($config);
        }

        admin_toastr('保存成功');
        return redirect('admin/config/create');
    }


    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Admin::grid(lsConfig::class, function (Grid $grid) {

            $grid->disableRowSelector();
            $grid->disableActions();
            // $grid->actions(function ($actions) {
            //     $actions->disableDelete();
            //     $actions->disableEdit();
            //     $actions->disableView();
            // });

            $grid->id('ID')->sortable();
            $grid->key()->sortable();
            $grid->value()->sortable();
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
        return Admin::form(lsConfig::class, function (Form $form) {
            // 获取数据库lsconfigs表的数据
            // $configs = lsConfig();

            $form->disableReset();
            $form->tools(function (Form\Tools $tools) {
                // 去掉返回按钮
                $tools->disableBackButton();
                // 去掉跳转列表按钮
                // $tools->disableListButton();
            });

            $form->tab('全局设置', function($form) {
                $form->text('system_name', '系统名称')->default(lsConfig('system_name'));
                $form->text('tel', '客服电话')->default(lsConfig('tel'));
                $form->image('qrcode', '客服二维码')->uniqueName()->removable()->value(lsConfig('qrcode'));
            })
            ->tab('短信设置', function($form) {
                $form->text('sms_key', '阿里云应用Key')->default(lsConfig('sms_key'));
                $form->text('sms_secret', '应用Secret')->default(lsConfig('sms_secret'));
                $form->text('sms_sign_name', '模板签名')->default(lsConfig('sms_sign_name'));
                $form->text('sms_temp_code', '模板ID')->default(lsConfig('sms_temp_code'));
            })
            ->tab('微信设置', function($form) {
                $form->text('wechat_appid', '应用Key')->default(lsConfig('wechat_appid'));
                $form->text('wechat_secret', '应用Secret')->default(lsConfig('wechat_secret'));
            });
        });
    }







}
