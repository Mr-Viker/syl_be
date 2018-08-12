<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSmsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sms', function (Blueprint $table) {
            $table->increments('id');
            $table->string('phone')->comment('手机号');
            $table->string('code')->comment('验证码');
            $table->string('type')->default('register')->comment('使用类型：默认是register');
            $table->text('result')->comment('第三方返回结果')->nullable();
            $table->tinyInteger('status')->default(0)->comment('状态：0正常 1已过期');
            $table->string('user_id')->comment('用户ID或IP 可为空')->nullable();
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
        Schema::dropIfExists('sms');
    }
}
