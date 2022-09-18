<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTopic extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('topic', function (Blueprint $table) {
            $table->id();
            $table->string('title', 100)->default('')->comment('标题');
            $table->string('keywords', 100)->default('')->comment('关键字');
            $table->string('summary')->default('')->comment('简介');
            $table->integer('course_count')->unsigned()->default(0)->comment('课程数量');
            $table->integer('published')->unsigned()->default(1)->comment('发布标识');
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
        Schema::dropIfExists('topic');
    }
}
