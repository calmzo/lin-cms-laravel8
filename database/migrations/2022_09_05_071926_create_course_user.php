<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCourseUser extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('course_user', function (Blueprint $table) {
            $table->id();
            $table->integer('course_id')->unsigned()->default(0)->comment('课程id');
            $table->integer('user_id')->unsigned()->default(0)->comment('用户id');
            $table->integer('plan_id')->unsigned()->default(0)->comment('计划id');
            $table->integer('role_type')->unsigned()->default(1)->comment('角色类型');
            $table->integer('source_type')->unsigned()->default(1)->comment('来源类型');
            $table->integer('duration')->unsigned()->default(0)->comment('学习时长');
            $table->integer('progress')->unsigned()->default(0)->comment('学习进度');
            $table->integer('reviewed')->unsigned()->default(0)->comment('评价标识');
            $table->integer('expiry_time')->unsigned()->default(0)->comment('过期时间');
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
        Schema::dropIfExists('course_user');
    }
}
