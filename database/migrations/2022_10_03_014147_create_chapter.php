<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChapter extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chapter', function (Blueprint $table) {
            $table->id();
            $table->string('title', 100)->default('')->comment('标题');
            $table->string('summary', 255)->default('')->comment('简介');
            $table->integer('parent_id')->unsigned()->default(0)->comment('父级编号');
            $table->integer('course_id')->unsigned()->default(0)->comment('课程编号');
            $table->integer('priority')->unsigned()->default(30)->comment('优先级');
            $table->integer('free')->unsigned()->default(0)->comment('免费标识');
            $table->integer('model')->default(0)->unsigned()->comment('模型类型');
            $table->string('attrs', 1000)->default('')->comment('扩展属性');
            $table->integer('published')->unsigned()->default(0)->comment('发布标识');
            $table->integer('resource_count')->unsigned()->default(0)->comment('资料数');
            $table->integer('lesson_count')->unsigned()->default(0)->comment('课时数');
            $table->integer('user_count')->unsigned()->default(0)->comment('学员数');
            $table->integer('consult_count')->unsigned()->default(0)->comment('咨询数');
            $table->integer('comment_count')->unsigned()->default(0)->comment('评论数量');
            $table->integer('like_count')->unsigned()->default(0)->comment('点赞数');
            $table->timestamp('create_time');
            $table->timestamp('update_time');
            $table->softDeletes('delete_time');
            $table->index('course_id');
            $table->index('parent_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('chapter');
    }
}
