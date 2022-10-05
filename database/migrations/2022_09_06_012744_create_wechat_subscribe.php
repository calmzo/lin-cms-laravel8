<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWechatSubscribe extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wechat_subscribe', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->unsigned()->default(0)->comment('用户id');
            $table->string('open_id', 64)->default('')->comment('开放ID');
            $table->string('union_id', 64)->default('')->comment('联合ID');
            $table->timestamp('create_time');
            $table->timestamp('update_time');
            $table->integer('deleted')->unsigned()->default(0);
            $table->index('user_id', 'user_id');
            $table->index('open_id', 'phone', 'BTREE');
            $table->index('union_id', 'union_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('wechat_subscribe');
    }
}
