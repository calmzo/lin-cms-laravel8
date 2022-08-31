<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRefund extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('refund', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->default(0)->comment('用户id');
            $table->integer('order_id')->default(0)->comment('订单编号');
            $table->integer('trade_id')->default(0)->comment('交易id');
            $table->string('sn', 32)->comment('退款序号');
            $table->string('subject', 100)->comment('退款主题');
            $table->decimal('amount', 10)->comment('退款金额');
            $table->integer('status')->comment('状态类型');
            $table->string('apply_note', 255)->comment('申请备注');
            $table->string('review_note', 255)->comment('审核备注');
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
        Schema::dropIfExists('refund');
    }
}
