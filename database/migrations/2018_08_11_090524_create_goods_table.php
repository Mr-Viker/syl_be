<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGoodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('goods', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('cate_id')->comment('所属分类');
            $table->string('title')->comment('商品名称');
            $table->float('price')->default(0.00)->comment('价格');
            $table->integer('amount')->comment('库存')->nullable();
            $table->integer('sold')->comment('已售数量')->nullable();
            $table->string('thumb')->comment('缩略图')->nullable();
            $table->string('sku')->comment('规格')->nullable();
            $table->float('freight')->default(0.00)->comment('运费')->nullable();
            $table->text('desc')->comment('商品介绍')->nullable();
            $table->tinyInteger('status')->comment('状态：0正常 1下架');
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
        Schema::dropIfExists('goods');
    }
}
