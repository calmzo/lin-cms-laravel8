<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotification extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notification', function (Blueprint $table) {
            $table->id();
            $table->integer('sender_id')->unsigned()->default(0)->comment('发送方编号');
            $table->integer('receiver_id')->unsigned()->default(0)->comment('接收方编号');
            $table->integer('event_id')->unsigned()->default(0)->comment('事件编号');
            $table->integer('event_type')->unsigned()->default(0)->comment('事件类型');
            $table->string('event_info', 1500)->default('')->comment('事件内容');
            $table->integer('viewed')->unsigned()->default(0)->comment('已读标识');
            $table->timestamp('create_time');
            $table->timestamp('update_time');
            $table->softDeletes('delete_time');
            $table->index('sender_id');
            $table->index('receiver_id');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('notification');
    }
}
