<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrder extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order', function (Blueprint $table) {
            $table->id();
            $table->string('sn', 32)->default('')->comment('订单编号');
            $table->string('subject', 100)->default('')->comment('订单标题');
            $table->decimal('amount', 10)->default('0.00')->comment('订单金额');
            $table->integer('user_id')->unsigned()->default(0)->comment('用户id');
            $table->integer('item_id')->unsigned()->default(0)->comment('条目id');
            $table->integer('item_type')->unsigned()->default(0)->comment('条目类型');
            $table->string('item_info', 3000)->default('')->comment('条目内容');
            $table->integer('promotion_id')->unsigned()->default(0)->comment('促销编号');
            $table->integer('promotion_type')->unsigned()->default(0)->comment('促销类型 1=限时秒杀 2=限时折扣');
            $table->string('promotion_info', 1000)->default('')->comment('促销信息');
            $table->integer('client_type')->default(1)->comment('终端类型');
            $table->string('client_ip', 64)->default('')->comment('终端IP');
            $table->integer('status')->default(1)->comment('状态类型');
            $table->timestamp('create_time');
            $table->timestamp('update_time');
            $table->integer('deleted')->unsigned()->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order');
    }
}
