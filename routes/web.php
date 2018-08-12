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
Route::group(['prefix' => 'api'], function() {
  Route::any('sms', 'SmsController@send');
  Route::any('upload', 'UploadController@upload');
  Route::any('config', 'ConfigController@list');
  // user
  Route::any('user/register', 'UserController@register');
  Route::any('user/login', 'UserController@login');



  /**
   * 需要登录才能调用的接口
   */
  Route::group(['middleware' => 'lauth'], function() {
    // user
    Route::any('user/info', 'UserController@info');
    Route::any('user/logout', 'UserController@logout');
    Route::any('user/uploadAvatar', 'UserController@uploadAvatar');
    Route::any('user/update', 'UserController@update');


  });
});




















