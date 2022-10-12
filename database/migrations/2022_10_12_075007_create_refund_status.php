<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRefundStatus extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('refund_status', function (Blueprint $table) {
            $table->id();
            $table->integer('refund_id')->unsigned()->default(0)->comment('退款编号');
            $table->integer('status')->unsigned()->default(1)->comment('订单状态');
            $table->timestamp('create_time');
            $table->index('refund_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('refund_status');
    }
}
