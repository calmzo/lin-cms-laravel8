<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHelp extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('help', function (Blueprint $table) {
            $table->id();
            $table->integer('category_id')->unsigned()->default(0)->comment('分类编号');
            $table->string('title', 100)->default('')->comment('标题');
            $table->text('content')->comment('内容');
            $table->integer('priority')->unsigned()->default(10)->comment('优先级');
            $table->integer('published')->unsigned()->default(0)->comment('发布标识');
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
        Schema::dropIfExists('help');
    }
}
