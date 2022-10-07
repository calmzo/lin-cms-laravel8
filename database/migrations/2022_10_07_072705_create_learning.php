<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLearning extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('learning', function (Blueprint $table) {
            $table->id();
            $table->string('request_id', 64)->default('')->comment('请求编号');
            $table->integer('course_id')->unsigned()->default(0)->comment('课程编号');
            $table->integer('chapter_id')->unsigned()->default(0)->comment('课时编号');
            $table->integer('user_id')->unsigned()->default(0)->comment('用户编号');
            $table->integer('plan_id')->unsigned()->default(0)->comment('计划编号');
            $table->integer('duration')->unsigned()->default(0)->comment('学习时长');
            $table->integer('position')->unsigned()->default(0)->comment('播放位置');
            $table->integer('client_type')->unsigned()->default(0)->comment('终端类型');
            $table->string('client_ip', 64)->default('')->comment('终端IP');
            $table->integer('active_time')->unsigned()->default(0)->comment('终端IP');
            $table->timestamp('create_time')->comment('创建时间');
            $table->timestamp('update_time')->comment('更新时间');
            $table->integer('deleted')->unsigned()->default(0)->comment('删除标识');
            $table->index('request_id');
            $table->index(['chapter_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('learning');
    }
}
