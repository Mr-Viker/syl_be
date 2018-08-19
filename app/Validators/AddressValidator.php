<?php
/**
 *  地址验证器
 */
namespace App\Validators;

class AddressValidator extends BaseValidator {

  protected $rules = [
    'id' => 'required',
    'name' => 'required',
    'tel' => 'required',
    'province' => 'required',
    'city' => 'required',
    'county' => 'required',
    'address_detail' => 'required',

  ];

  protected $msgs =[
    'address_detail.required' => '详细地址不能为空',
  ];

  protected $scenes = [
    'store' => ['name', 'tel', 'province', 'city', 'county', 'address_detail'],
    'update' => ['id', 'name', 'tel', 'province', 'city', 'county', 'address_detail'],
  ];

}