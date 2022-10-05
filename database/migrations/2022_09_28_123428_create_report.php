<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReport extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('report', function (Blueprint $table) {
            $table->id();
            $table->string('reason', 1000)->default('')->comment('理由');
            $table->integer('user_id')->unsigned()->default(0)->comment('用户编号');
            $table->integer('item_id')->unsigned()->default(0)->comment('条目编号');
            $table->integer('item_type')->unsigned()->default(0)->comment('条目类型');
            $table->integer('client_type')->unsigned()->default(0)->comment('终端类型');
            $table->string('client_ip', 64)->default('')->comment('终端IP');
            $table->integer('reviewed')->unsigned()->default(0)->comment('处理标识');
            $table->integer('accepted')->unsigned()->default(0)->comment('采纳标识');
            $table->timestamp('create_time');
            $table->timestamp('update_time');
            $table->integer('deleted')->unsigned()->default(0);
            $table->index('user_id');
            $table->index(['item_id', 'item_type']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('report');
    }
}
