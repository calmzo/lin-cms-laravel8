<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUser extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user', function (Blueprint $table) {
            $table->integer('id')->primary()->default(0)->comment('主键编号');
            $table->string('name', 30)->default('')->comment('名称');
            $table->string('avatar', 100)->default('')->comment('头像');
            $table->string('title', 30)->default('')->comment('头衔');
            $table->string('about', 255)->default('')->comment('简介');
            $table->string('area', 30)->default('')->comment('地区');
            $table->integer('gender')->default(3)->comment('性别');
            $table->integer('vip')->default(0)->comment('会员标识');
            $table->integer('course_count')->default(0)->comment('课程数');
            $table->integer('article_count')->default(0)->comment('文章数量');
            $table->integer('question_count')->default(0)->comment('提问数');
            $table->integer('answer_count')->default(0)->comment('回答数');
            $table->integer('comment_count')->default(0)->comment('评论数');
            $table->integer('favorite_count')->default(0)->comment('收藏数');
            $table->integer('report_count')->default(0)->comment('举报数');
            $table->timestamp('vip_expiry_time')->nullable()->comment('会员期限');
            $table->timestamp('active_time')->nullable()->comment('活跃时间');
            $table->integer('locked')->default(0)->comment('锁定表示');
            $table->integer('edu_role')->nullable()->default(1)->comment('教学角色');
            $table->integer('admin_role')->nullable()->default(0)->comment('后台角色');
            $table->timestamp('lock_expiry_time')->nullable()->comment('锁定期限');
            $table->timestamp('create_time');
            $table->timestamp('update_time');
            $table->softDeletes('delete_time');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user');
    }
}
