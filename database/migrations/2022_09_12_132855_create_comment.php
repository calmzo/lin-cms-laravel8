<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateComment extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comment', function (Blueprint $table) {
            $table->id();
            $table->string('content', 1000)->default('')->comment('内容');
            $table->integer('user_id')->unsigned()->default(0)->comment('用户id');
            $table->integer('parent_id')->unsigned()->default(0)->comment('父级id');
            $table->integer('to_user_id')->unsigned()->default(0)->comment('回复用户');
            $table->integer('item_id')->unsigned()->default(0)->comment('条目id');
            $table->integer('item_type')->unsigned()->default(0)->comment('条目类型');
            $table->integer('client_type')->unsigned()->default(0)->comment('终端类型');
            $table->string('client_ip', 64)->default('')->comment('终端IP');
            $table->integer('published')->unsigned()->default(0)->comment('发布标识');
            $table->integer('reply_count')->unsigned()->default(0)->comment('回复数');
            $table->integer('like_count')->unsigned()->default(0)->comment('点赞数');
            $table->integer('report_count')->unsigned()->default(0)->comment('举报数');
            $table->timestamp('create_time');
            $table->timestamp('update_time');
            $table->integer('deleted')->unsigned()->default(0);
            $table->index(['item_id', 'item_type']);
            $table->index('user_id');
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
        Schema::dropIfExists('comment');
    }
}
