<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateArticle extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('article', function (Blueprint $table) {
            $table->id();
            $table->string('title', 100)->default('')->comment('标题');
            $table->string('cover', 100)->default('')->comment('封面');
            $table->string('summary')->default('')->comment('摘要');
            $table->string('tags')->default('')->comment('标签');
            $table->text('content')->comment('内容');
            $table->float('score', 10)->unsigned()->default(0.00);
            $table->integer('category_id')->unsigned()->default(0)->comment('分类id');
            $table->integer('user_id')->unsigned()->default(0)->comment('用户id');
            $table->integer('source_type')->unsigned()->default(0)->comment('来源类型');
            $table->string('source_url', 100)->default('')->comment('来源网址');
            $table->integer('client_type')->unsigned()->default(0)->comment('终端类型');
            $table->string('client_ip', 64)->default('')->comment('终端IP');
            $table->integer('private')->unsigned()->default(0)->comment('私有标识');
            $table->integer('closed')->unsigned()->default(1)->comment('允许评论');
            $table->integer('featured')->unsigned()->default(0)->comment('推荐标识');
            $table->integer('published')->unsigned()->default(0)->comment('发布标识');
            $table->integer('word_count')->unsigned()->default(0)->comment('文字数');
            $table->integer('view_count')->unsigned()->default(0)->comment('浏览数');
            $table->integer('comment_count')->unsigned()->default(0)->comment('评论数');
            $table->integer('like_count')->unsigned()->default(0)->comment('点赞数');
            $table->integer('report_count')->unsigned()->default(0)->comment('举报数');
            $table->integer('favorite_count')->unsigned()->default(0)->comment('收藏数');
            $table->timestamp('create_time');
            $table->timestamp('update_time');
            $table->integer('deleted')->unsigned()->default(0);
            $table->index('user_id');
            $table->index('category_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('article');
    }
}
