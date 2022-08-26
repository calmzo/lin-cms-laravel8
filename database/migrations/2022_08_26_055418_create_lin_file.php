<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLinFile extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lin_file', function (Blueprint $table) {
            $table->id();
            $table->string('path', 500)->comment('路径');
            $table->integer('type')->default(1)->comment('1 local，其他表示其他地方');
            $table->string('name', 100)->comment('名称');
            $table->string('extension', 50)->comment('后缀');
            $table->integer('size')->default(1)->comment('大小');
            $table->string('md5', 40)->comment('图片md5值，防止上传重复图片');
            $table->timestamp('create_time');
            $table->timestamp('update_time');
            $table->softDeletes('delete_time');
            $table->index(['md5']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lin_file');
    }
}
