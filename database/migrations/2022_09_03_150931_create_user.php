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
            $table->id();
            $table->string('name', 30)->default('')->comment('名称');
            $table->string('avatar', 100)->default('')->comment('头像');
            $table->string('title', 30)->default('')->comment('头衔');
            $table->string('about', 255)->default('')->comment('简介');
            $table->string('area', 30)->default('')->comment('地区');
            $table->integer('gender')->default(3)->comment('性别');
            $table->integer('vip')->default(0)->comment('会员标识');
            $table->integer('locked')->default(0)->comment('锁定表示');
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
