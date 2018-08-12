<?php
/**
 *  用户验证器
 */
namespace App\Validators;

class UserValidator extends BaseValidator {

  protected $rules = [
    'username' => 'required',
    'phone' => 'required|regex: /^\d+$/',
    'oldPassword' => 'required|sameOld',
    'password' => 'required',
    // 'password_confirm' => 'required|same:password',
    'confirmPassword' => 'required|same:password',
    'captcha' => 'required|captcha',
    'smsCode' => 'required',
    'type' => 'required',
  ];

  protected $msgs =[
    'type.required' => '操作不能为空',
  ];

  protected $scenes = [
    // 'admin.register' => ['username', 'phone', 'password'],
    'sms' => ['phone', 'type'],
    'register' => ['username', 'phone', 'password', 'smsCode'],
    'login' => ['phone', 'password'],
    'changePassword' => ['oldPassword', 'password', 'confirmPassword'],
  ];

}