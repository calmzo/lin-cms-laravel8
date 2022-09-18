<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('category', function (Blueprint $table) {
            $table->id();
            $table->integer('parent_id')->unsigned()->default(0)->comment('父级id');
            $table->integer('level')->unsigned()->default(0)->comment('层级');
            $table->integer('type')->unsigned()->default(0)->comment('类型');
            $table->string('name', 30)->default('')->comment('名称');
            $table->string('alias', 30)->default('')->comment('别名');
            $table->string('icon', 100)->default('')->comment('图标');
            $table->string('path', 30)->default('')->comment('路径');
            $table->integer('priority')->unsigned()->default(30)->comment('优先级');
            $table->integer('published')->unsigned()->default(0)->comment('发布标识');
            $table->integer('child_count')->unsigned()->default(0)->comment('节点数');
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
        Schema::dropIfExists('category');
    }
}
