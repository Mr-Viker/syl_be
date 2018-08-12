<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBigPicsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('big_pics', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title')->comment('标题')->nullable();
            $table->string('img')->comment('图片地址');
            $table->string('link')->comment('链接url')->nullable();
            $table->tinyInteger('status')->comment('状态：0显示 1不显示');
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
        Schema::dropIfExists('big_pics');
    }
}
