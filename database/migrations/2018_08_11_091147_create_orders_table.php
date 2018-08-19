<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->index()->comment('用户ID');
            $table->integer('goods_id')->index()->comment('商品ID');
            $table->float('price')->comment('购买时单价');
            $table->integer('num')->default('1')->comment('购买数量');
            $table->float('total')->comment('购买总价');
            $table->string('realname')->comment('收货人姓名');
            $table->string('phone')->comment('收货人手机号码');
            $table->string('address')->comment('收货地址');
            $table->tinyInteger('status')->comment('状态：0待付款 1待发货 2待确认收货 3已完成 4已取消');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
