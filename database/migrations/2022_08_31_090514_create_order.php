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
            $table->string('sn', 32)->comment('订单编号');
            $table->string('subject', 100)->comment('订单标题');
            $table->decimal('amount', 10)->default('0.00')->comment('订单金额');
            $table->integer('user_id')->default(0)->comment('用户id');
            $table->integer('client_type')->default(1)->comment('终端类型');
            $table->string('client_ip', 64)->comment('终端IP');
            $table->integer('status')->comment('状态类型');
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
        Schema::dropIfExists('order');
    }
}
