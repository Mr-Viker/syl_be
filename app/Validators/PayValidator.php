<?php
/**
 *  支付验证器
 */
namespace App\Validators;

class PayValidator extends BaseValidator {

  protected $rules = [
    'orderId' => 'required',
    'type' => 'required',
  ];

  protected $msgs =[
    'orderId.required' => '订单ID不能为空',
    'type.required' => '支付类型不能为空',
  ];

  protected $scenes = [
    'pay' => ['orderId', 'type'],
  ];

}