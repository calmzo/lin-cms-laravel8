<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChapterUser extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chapter_user', function (Blueprint $table) {
            $table->id();
            $table->integer('course_id')->unsigned()->default(0)->comment('课程编号');
            $table->integer('chapter_id')->unsigned()->default(0)->comment('章节编号');
            $table->integer('user_id')->unsigned()->default(0)->comment('用户编号');
            $table->integer('plan_id')->unsigned()->default(0)->comment('计划编号');
            $table->integer('duration')->unsigned()->default(0)->comment('学习时长');
            $table->integer('position')->unsigned()->default(0)->comment('播放位置');
            $table->integer('progress')->unsigned()->default(0)->comment('学习进度');
            $table->integer('consumed')->unsigned()->default(0)->comment('消费标识');
            $table->timestamp('create_time')->comment('创建时间');
            $table->timestamp('update_time')->comment('更新时间');
            $table->integer('deleted')->unsigned()->default(0)->comment('删除标识');
            $table->index(['chapter_id', 'user_id']);
            $table->index(['course_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('chapter_user');
    }
}
