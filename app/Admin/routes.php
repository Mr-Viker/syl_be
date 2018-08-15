<?php

use Illuminate\Routing\Router;

Admin::registerAuthRoutes();

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
], function (Router $router) {

    $router->get('/', 'HomeController@index');
    $router->resource('user', UserController::class);
    $router->resource('config', ConfigController::class);
    $router->resource('sms', SmsController::class);
    $router->resource('carousel', CarouselController::class);
    $router->resource('big_pic', BigPicController::class);
    $router->resource('goods', GoodsController::class);
    $router->resource('cate', CateController::class);
    $router->resource('order', OrderController::class);
    $router->resource('pay', PayController::class);

});
