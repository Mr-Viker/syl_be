<?php
/**
 *  订单验证器
 */
namespace App\Validators;

class OrderValidator extends BaseValidator {

  protected $rules = [
    'userId' => 'required',
    'goodsId' => 'required',
    'realname' => 'required',
    'phone' => 'required',
    'address' => 'required',
    'price' => 'required',
    'num' => 'required',
    'id' => 'required|numeric',
    'addressId' => 'required|numeric',
  ];

  protected $msgs =[

  ];

  protected $scenes = [
    'store' => ['goodsId', 'addressId', 'price', 'num'],
    'cancel' => ['id'],
    'check' => ['id'],
    'confirm' => ['id'],
  ];

}