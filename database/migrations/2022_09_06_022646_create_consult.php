<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConsult extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('consult', function (Blueprint $table) {
            $table->id();
            $table->integer('course_id')->unsigned()->default(0)->comment('课程编号');
            $table->integer('chapter_id')->unsigned()->default(0)->comment('章节编号');
            $table->integer('user_id')->unsigned()->default(0)->comment('用户编号');
            $table->integer('client_type')->unsigned()->default(0)->comment('终端类型');
            $table->string('client_ip', 64)->default('')->comment('终端IP');
            $table->string('question', 1500)->default('')->comment('问题');
            $table->string('answer', 1500)->default('')->comment('答案');
            $table->integer('rating')->unsigned()->default(0)->comment('评分');
            $table->integer('priority')->unsigned()->default(0)->comment('优先级');
            $table->integer('private')->unsigned()->default(0)->comment('私密标识');
            $table->integer('published')->unsigned()->default(0)->comment('发布标识');
            $table->integer('like_count')->unsigned()->default(0)->comment('点赞数');
            $table->integer('report_count')->unsigned()->default(0)->comment('举报数');
            $table->integer('reply_time')->unsigned()->default(0)->comment('回复时间');
            $table->timestamp('create_time');
            $table->timestamp('update_time');
            $table->integer('deleted')->unsigned()->default(0);
            $table->index('course_id', 'course_id');
            $table->index('chapter_id', 'chapter_id');
            $table->index('user_id', 'user_id', 'BTREE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('consult');
    }
}
