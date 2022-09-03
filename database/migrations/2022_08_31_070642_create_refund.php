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
            $table->string('sn', 32)->default('')->comment('退款序号');
            $table->string('subject', 100)->default('')->comment('退款主题');
            $table->decimal('amount', 10)->default(0.00)->comment('退款金额');
            $table->integer('status')->default(1)->comment('状态类型');
            $table->string('apply_note', 255)->default('')->comment('申请备注');
            $table->string('review_note', 255)->default('')->comment('审核备注');
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
