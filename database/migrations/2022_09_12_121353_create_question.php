<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuestion extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('question', function (Blueprint $table) {
            $table->id();
            $table->integer('category_id')->unsigned()->default(0)->comment('分类id');
            $table->integer('user_id')->unsigned()->default(0)->comment('用户id');
            $table->integer('last_replier_id')->unsigned()->default(0)->comment('最后回应用户');
            $table->integer('last_answer_id')->unsigned()->default(0)->comment('最后答案编号');
            $table->integer('accept_answer_id')->unsigned()->default(0)->comment('采纳答案编号');
            $table->string('title', 100)->default('')->comment('标题');
            $table->string('cover', 100)->default('')->comment('封面');
            $table->string('summary')->default('')->comment('摘要');
            $table->string('tags')->default('')->comment('标签');
            $table->text('content')->comment('内容');
            $table->float('score', 10)->unsigned()->default(0.00);
            $table->integer('bounty')->unsigned()->default(0)->comment('悬赏积分');
            $table->integer('anonymous')->unsigned()->default(0)->comment('匿名标识');
            $table->integer('solved')->unsigned()->default(0)->comment('解决标识');
            $table->integer('closed')->unsigned()->default(0)->comment('关闭标识');
            $table->integer('published')->unsigned()->default(0)->comment('发布标识');
            $table->integer('client_type')->unsigned()->default(0)->comment('终端类型');
            $table->string('client_ip', 64)->default('')->comment('终端IP');
            $table->integer('view_count')->unsigned()->default(0)->comment('浏览数');
            $table->integer('answer_count')->unsigned()->default(0)->comment('答案数');
            $table->integer('comment_count')->unsigned()->default(0)->comment('评论数');
            $table->integer('favorite_count')->unsigned()->default(0)->comment('收藏数');
            $table->integer('like_count')->unsigned()->default(0)->comment('点赞数');
            $table->integer('report_count')->unsigned()->default(0)->comment('举报数');
            $table->timestamp('last_reply_time')->nullable()->comment('回应时间');
            $table->timestamp('create_time');
            $table->timestamp('update_time');
            $table->softDeletes('delete_time');
            $table->index('user_id');
            $table->index('last_reply_time');
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
        Schema::dropIfExists('question');
    }
}
