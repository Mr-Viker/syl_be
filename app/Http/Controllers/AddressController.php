<?php
/**
 * 地址控制器
 */
namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\User;
use App\Validators\AddressValidator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AddressController extends Controller
{
  // 地址列表
  public function index(Request $req) {
    $user = User::find($req->userInfo->id);
    $res = $user->addresses;
    return ['code' => '00', 'data' => $res, 'msg' => '获取成功'];
  }

  // 详情
  public function show(Request $req) {
    $data = $req->only(['id']);
    if (empty($data['id'])) {
      return ['code' => '01', 'msg' => '缺少地址ID'];
    }
    $user = User::find($req->userInfo->id);
    try {
      $res = $user->addresses()->where($data)->first();
    } catch(\Exception $e) {
      return ['code' => '500', 'msg' => $e->getMessage()];
    }
    return ['code' => '00', 'data' => $res, 'msg' => '获取成功'];
  }

  // 保存
  public function store(Request $req) {
    $data = $req->only(['name', 'tel', 'province', 'city', 'county', 'address_detail', 'area_code', 'postal_code', 'is_default']);
    // 验证
    $valid = AddressValidator::handle($data, 'store');
    if (true !== $valid) {
      return ['code' => '01', 'msg' => $valid->first()];
    }
    // 入库
    $address = new Address();
    $address->user_id = $req->userInfo->id;
    $address->realname = $data['name'];
    $address->phone = $data['tel'];
    $address->province = $data['province'];
    $address->city = $data['city'];
    $address->county = $data['county'];
    $address->area = $data['address_detail'];
    $address->area_code = $data['area_code'];
    empty($data['postal_code']) ?: $address->postcode = $data['postal_code'];
    $address->is_default = $data['is_default'] ? 1 : 0;
    try {
      // 如果设置了为默认地址 则需要检测该用户是否有默认地址了 如果有则修改
      if ($address->is_default) {
        DB::beginTransaction(); //开启事务
        $user = User::find($req->userInfo->id);
        $defaultAddress = $user->addresses()->where('is_default', 1)->first();
        if ($defaultAddress) {
          $defaultAddress->is_default = 0;
          $defaultAddress->update();
        }
      }
      $address->save();
      DB::commit();
    } catch(\Exception $e) {
      DB::rollBack();
      return ['code' => '500', 'msg' => $e->getMessage()];
    }
    return ['code' => '00', 'msg' => '保存成功'];
  }


  // 更新
  public function update(Request $req) {
    $data = $req->only(['id', 'name', 'tel', 'province', 'city', 'county', 'address_detail', 'area_code', 'postal_code', 'is_default']);
    // 验证
    $valid = AddressValidator::handle($data, 'update');
    if (true !== $valid) {
      return ['code' => '01', 'msg' => $valid->first()];
    }
    // 入库
    $address = Address::find($data['id']);
    $address->realname = $data['name'];
    $address->phone = $data['tel'];
    $address->province = $data['province'];
    $address->city = $data['city'];
    $address->county = $data['county'];
    $address->area = $data['address_detail'];
    $address->area_code = $data['area_code'];
    empty($data['postal_code']) ?: $address->postcode = $data['postal_code'];
    $address->is_default = $data['is_default'] ? 1 : 0;
    try {
      // 如果设置了为默认地址 则需要检测该用户是否有默认地址了 如果有则修改
      if ($address->is_default) {
        DB::beginTransaction(); //开启事务
        $user = User::find($req->userInfo->id);
        $defaultAddress = $user->addresses()->where('is_default', 1)->first();
        if ($defaultAddress) {
          $defaultAddress->is_default = 0;
          $defaultAddress->update();
        }
      }
      $address->update();
      DB::commit();
    } catch(\Exception $e) {
      DB::rollBack();
      return ['code' => '500', 'msg' => $e->getMessage()];
    }
    return ['code' => '00', 'msg' => '更新成功'];
  }
  
  // 删除
  public function destroy(Request $req) {
    $data = $req->only(['id']);
    if (empty($data['id'])) {
      return ['code' => '01', 'msg' => '缺少地址ID'];
    }
    // 删除
    try {
      $res = Address::destroy($data['id']);
    } catch(\Exception $e) {
      return ['code' => '500', 'msg' => $e->getMessage()];
    }
    return ['code' => '00', 'msg' => '删除成功'];
  }

}
