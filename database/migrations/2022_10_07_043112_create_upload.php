<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUpload extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('upload', function (Blueprint $table) {
            $table->id();
            $table->integer('type')->unsigned()->default(0)->comment('条目类型');
            $table->string('name', 100)->unique()->default('')->comment('文件名');
            $table->string('path', 100)->default('')->comment('路径');
            $table->string('mime', 100)->default('')->comment('mime');
            $table->string('md5', 100)->default('')->comment('md5');
            $table->integer('size')->unsigned()->default(0)->comment('大小');
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
        Schema::dropIfExists('upload');
    }
}
