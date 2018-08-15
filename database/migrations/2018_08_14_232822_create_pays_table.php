<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pays', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('order_id')->index()->comment('订单ID');
            $table->integer('user_id')->index()->comment('用户ID');
            $table->string('type')->comment('支付类型：wechat alipay');
            $table->tinyInteger('status')->default(0)->comment('状态：0未支付 1成功 2失败');
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
        Schema::dropIfExists('pays');
    }
}
