<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('username', 50);
            $table->string('phone', 50)->unique();
            $table->string('password');
            $table->string('avatar')->nullable();
            $table->float('balance')->default('0.00')->nullable();
            $table->tinyInteger('autoplay')->default(0)->nullable();
            $table->tinyInteger('status')->default(0)->nullable()->comment('状态：0正常 1冻结');
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
        Schema::dropIfExists('users');
    }
}
