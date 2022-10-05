<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePointHistory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('point_history', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->unsigned()->default(0)->comment('用户id');
            $table->string('user_name', 30)->default('')->comment('用户名称');
            $table->integer('event_id')->unsigned()->default(0)->comment('事件id');
            $table->integer('event_type')->unsigned()->default(0)->comment('事件类型');
            $table->string('event_info', 1000)->default('')->comment('事件内容');
            $table->integer('event_point')->unsigned()->default(0)->comment('事件积分');
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
        Schema::dropIfExists('point_history');
    }
}
