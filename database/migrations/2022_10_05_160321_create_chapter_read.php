<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChapterRead extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chapter_read', function (Blueprint $table) {
            $table->id();
            $table->integer('course_id')->unsigned()->default(0)->comment('课程编号');
            $table->integer('chapter_id')->unsigned()->default(0)->comment('章节编号');
            $table->text('content')->comment('内容');
            $table->timestamp('create_time')->comment('创建时间');
            $table->timestamp('update_time')->comment('更新时间');
            $table->integer('deleted')->unsigned()->default(0)->comment('删除标识');
            $table->index('chapter_id');
            $table->index('course_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('chapter_read');
    }
}
