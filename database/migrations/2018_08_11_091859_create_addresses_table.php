<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAddressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('addresses', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->index()->comment('用户ID');
            $table->string('realname')->comment('收货人姓名');
            $table->string('phone')->comment('收货人手机号码');
            $table->string('province')->comment('省');
            $table->string('city')->comment('市');
            $table->string('county')->comment('区');
            $table->string('area')->comment('详细地址');
            $table->string('area_code')->comment('省市区对应编码')->nullable();
            $table->string('postcode')->comment('邮编')->nullable();
            $table->tinyInteger('is_default')->comment('默认收货地址：0是 1否')->nullable();
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
        Schema::dropIfExists('addresses');
    }
}
