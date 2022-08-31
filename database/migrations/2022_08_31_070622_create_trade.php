<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrade extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trade', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->default(0)->comment('用户id');
            $table->integer('order_id')->default(0)->comment('订单编号');
            $table->string('sn', 32)->default('')->comment('交易序号');
            $table->string('subject', 100)->default('')->comment('交易主题');
            $table->decimal('amount', 10)->default(0.00)->comment('交易金额');
            $table->integer('channel')->default(0)->comment('平台类型');
            $table->string('channel_sn', 64)->default('')->comment('平台序号');
            $table->integer('status')->default(1)->comment('状态类型');
            $table->timestamp('create_time');
            $table->timestamp('update_time');
            $table->softDeletes('delete_time');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('trade');
    }
}
