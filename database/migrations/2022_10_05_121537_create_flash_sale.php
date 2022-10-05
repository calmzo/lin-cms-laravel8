<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFlashSale extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('flash_sale', function (Blueprint $table) {
            $table->id();
            $table->integer('item_id')->unsigned()->default(0)->comment('商品编号');
            $table->integer('item_type')->unsigned()->default(0)->comment('商品类型');
            $table->string('item_info', 1000)->default('')->comment('商品信息');
            $table->integer('start_time')->unsigned()->default(0)->comment('开始时间');
            $table->integer('end_time')->unsigned()->default(0)->comment('结束时间');
            $table->string('schedules')->default('')->comment('抢购场次');
            $table->decimal('price', 10)->default(0.00)->comment('抢购价格');
            $table->integer('stock')->unsigned()->default(0)->comment('抢购库存');
            $table->integer('published')->unsigned()->default(0)->comment('发布标识');
            $table->timestamp('create_time')->comment('创建时间');
            $table->timestamp('update_time')->comment('更新时间');
            $table->integer('deleted')->unsigned()->default(0)->comment('删除标识');
            $table->index('end_time');
            $table->index('start_time');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('flash_sale');
    }
}
