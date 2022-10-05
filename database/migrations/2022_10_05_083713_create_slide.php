<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSlide extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('slide', function (Blueprint $table) {
            $table->id();
            $table->string('title', 100)->default('')->comment('标题');
            $table->string('cover', 100)->default('')->comment('封面');
            $table->string('summary')->default('')->comment('简介');
            $table->string('content')->default('')->comment('内容');
            $table->integer('platform')->unsigned()->default(1)->comment('平台类型');
            $table->integer('target')->unsigned()->default(1)->comment('目标类型');
            $table->string('target_attrs', 1000)->default('')->comment('目标属性');
            $table->integer('priority')->unsigned()->default(10)->comment('优先级');
            $table->integer('published')->unsigned()->default(0)->comment('发布状态');
            $table->timestamp('create_time')->comment('创建时间');
            $table->timestamp('update_time')->comment('更新时间');
            $table->integer('deleted')->unsigned()->default(0)->comment('删除标识');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('slide');
    }
}
