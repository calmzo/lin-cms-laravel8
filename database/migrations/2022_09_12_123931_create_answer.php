<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAnswer extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('answer', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->unsigned()->default(0)->comment('用户id');
            $table->integer('question_id')->unsigned()->default(0)->comment('问题id');
            $table->string('cover', 100)->default('')->comment('封面');
            $table->string('summary')->default('')->comment('摘要');
            $table->text('content')->comment('内容');
            $table->integer('anonymous')->unsigned()->default(0)->comment('匿名标识');
            $table->integer('accepted')->unsigned()->default(0)->comment('采纳标识');
            $table->integer('published')->unsigned()->default(0)->comment('发布标识');
            $table->integer('client_type')->unsigned()->default(0)->comment('终端类型');
            $table->string('client_ip', 64)->default('')->comment('终端IP');
            $table->integer('comment_count')->unsigned()->default(0)->comment('评论数');
            $table->integer('like_count')->unsigned()->default(0)->comment('点赞数');
            $table->integer('report_count')->unsigned()->default(0)->comment('举报数');
            $table->timestamp('create_time');
            $table->timestamp('update_time');
            $table->softDeletes('delete_time');
            $table->index('user_id');
            $table->index('question_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('answer');
    }
}
