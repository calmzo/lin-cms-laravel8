<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTag extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tag', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50)->default('')->comment('名称');
            $table->string('alias', 50)->default('')->comment('别名');
            $table->string('icon', 100)->default('')->comment('图标');
            $table->string('scopes', 100)->default('')->comment('范围');
            $table->integer('priority')->unsigned()->default(0)->comment('优先级');
            $table->integer('published')->unsigned()->default(1)->comment('发布标识');
            $table->integer('follow_count')->unsigned()->default(0)->comment('关注数');
            $table->integer('course_count')->unsigned()->default(0)->comment('课程数');
            $table->integer('article_count')->unsigned()->default(0)->comment('文章数');
            $table->integer('question_count')->unsigned()->default(0)->comment('问题数');
            $table->timestamp('create_time');
            $table->timestamp('update_time');
            $table->integer('deleted')->unsigned()->default(0);
            $table->index('name');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tag');
    }
}
