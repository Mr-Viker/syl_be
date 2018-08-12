<?php

namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Validators\UserValidator;
use Encore\Admin\Controllers\ModelForm;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Layout\Row;
use Illuminate\Http\Request;
use Illuminate\Support\MessageBag;

class UserController extends Controller
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

            $content->header('用户管理');
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

            $content->header('用户管理');
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
            // if(\Session::has('errors')) {
            //     $content->withError(\Session::pull('errors')->all());
            // }
            $content->header('用户管理');
            $content->description('添加');
            $content->body($this->form());
        });
    }

    /**
     * Save interface.
     *
     * @return Content
     */
    // public function store(Request $request)
    // {
    //     $data = $request->all();
    //     $data['avatar'] = $request->file('avatar');
    //     // 验证
    //     $validation = UserValidator::handle($data, 'admin.register');
    //     // dd($validation);
    //     if (true !== $validation) {
    //         return back()->withInput()->withErrors($validation);
    //     }
    //     // 检测手机号是否已注册
    //     $user = new User();
    //     $isExists = $user->isExists($data['phone']);
    //     if ($isExists) {
    //         return back()->withInput()->withErrors(['手机号已注册']);
    //     }
    //     $data['password'] = \Hash::make($data['password']);
    //     // 入库
    //     list($user->username, $user->phone, $user->password, $user->avatar, $user->balance, $user->status)
    //     = [$data['username'], $data['phone'], $data['password'], $data['avatar'], $data['balance'], $data['status']];
    //     try {
    //         $user->save();
    //         return redirect('admin/user')->with(['msg' => '添加成功']);
    //     } catch(\Exception $e) {
    //         return back()->withInput()->withErrors($e->getMessage());
    //     }
    // }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Admin::grid(User::class, function (Grid $grid) {
            // $grid->disableRowSelector(); //checkbox
            // $grid->disableExport(); //导出

            $grid->id('ID')->sortable();
            $grid->avatar('头像')->image('', 50, 50);
            $grid->username('用户名');
            $grid->phone('手机号');
            $grid->balance('账户余额');
            $grid->created_at('创建时间');
            $grid->status('状态')->display(function($v) {
                if ($v == 0) {
                    return "<span class='label label-success'>正常</span>";
                } else {
                    return "<span class='label label-danger'>冻结</span>";
                }
            });
            // $grid->updated_at();
            $grid->filter(function($filter) {
                $filter->like('username', '用户名');
                $filter->like('phone', '手机号');
                $filter->date('created_at', '创建日期');
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
        return Admin::form(User::class, function (Form $form) {
            $form->display('id', 'ID');
            $form->text('username', '用户名')->rules('required');
            $form->text('phone', '手机号')->rules('required|regex:/^\d+$/');
            $form->password('password', '密码')->default('*************************')
                ->help('密码不能修改，新增用户默认密码123456')->attribute('readonly', true);
            $form->image('avatar', '头像')->uniqueName()->removable();
            $form->number('balance', '账户余额')->default(0.00);
            $form->radio('status', '状态')->options(['0' => '正常', '1'=> '冻结'])->default('0');
            // $form->display('created_at', 'Created At');
            // $form->display('updated_at', 'Updated At');

            $form->saving(function($form) {
                // 如果表单提交的手机号和模型里的手机号不一致 则需要判断手机号是否已注册
                if (empty($form->model()->id) || $form->phone != $form->model()->phone) {
                    $isExists = User::where('phone', $form->phone)->first();
                    if ($isExists) {
                        $error = new MessageBag([
                           'title'   => '验证失败',
                           'message' => '手机号已注册',
                       ]);
                       return back()->with(compact('error'));
                    }
                }

                // 如果是添加操作则加上加密后的密码 否则忽略密码
                if (empty($form->model()->id)) {
                    $form->password = \Hash::make('123456');
                } else {
                    $form->ignore(['password']);
                }
            });
        });
    }
}
