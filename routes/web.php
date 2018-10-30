<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });



/**
 * Page
 */
// Route::group([], function() {
//   // 首页
//   Route::get('/', 'IndexController@index');
//   Route::get('index', 'IndexController@index');
//   // 用户
//   Route::get('person', 'IndexController@person');
//   Route::get('register', 'IndexController@register');
//   Route::get('login', 'IndexController@login');



//   /**
//    * 需要登录才能访问的页面
//    */
//   Route::group(['middleware' => 'lauth'], function() {
//     Route::get('personal', 'IndexController@personal');

//   });
// });




/**
 * API
 */
Route::group(['prefix' => 'api', 'namespace' => 'API', 'middleware' => ['cors', 'parsetoken']], function() {
  Route::any('sms', 'SmsController@send');
  Route::any('upload', 'UploadController@upload');
  Route::any('config', 'ConfigController@list');
  // user
  Route::any('user/register', 'UserController@register');
  Route::any('user/login', 'UserController@login');
  Route::any('user/forgetPassword', 'UserController@forgetPassword'); 
  // goods
  Route::any('index/banner', 'IndexController@banner');
  Route::any('index/bigPic', 'IndexController@bigPic');
  Route::any('cate/list', 'GoodsController@cate');
  Route::any('goods/list', 'GoodsController@list');
  Route::any('goods/detail', 'GoodsController@detail');
  // mail
  Route::get('mail/send','MailController@send');

  /**
   * 防止token过期无法注册登录等情况
   */


  /**
   * 需要登录才能调用的接口
   */
  Route::group(['middleware' => 'lauth'], function() {
    // user
    Route::any('user/info', 'UserController@info');
    Route::any('user/logout', 'UserController@logout');
    Route::any('user/uploadAvatar', 'UserController@uploadAvatar');
    Route::any('user/update', 'UserController@update');
    // collect
    Route::any('collect/store', 'CollectController@store');
    Route::any('collect/destroy', 'CollectController@destroy');
    Route::any('collect/list', 'CollectController@index');
    // address
    Route::any('address/list', 'AddressController@index');
    Route::any('address/detail', 'AddressController@show');
    Route::any('address/store', 'AddressController@store');
    Route::any('address/update', 'AddressController@update');
    Route::any('address/destroy', 'AddressController@destroy');
    // order
    Route::any('order/store', 'OrderController@store');
    Route::any('order/list', 'OrderController@index');
    Route::any('order/cancel', 'OrderController@cancel');
    Route::any('order/check', 'OrderController@check');
    Route::any('order/confirm', 'OrderController@confirm');
    // pay
    Route::any('pay', 'PayController@pay');
    Route::any('pay/callback', 'PayController@callback');


  });
});




















