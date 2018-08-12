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
            $table->tinyInteger('status')->comment('状态：0未付款 1已付款 2已确认收货(完成) 3已取消');
            $table->integer('address_id')->comment('地址ID');
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
