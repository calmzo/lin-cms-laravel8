<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLinPermission extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lin_permission', function (Blueprint $table) {
            $table->id();
            $table->string('name', 60)->comment('权限名称，例如：访问首页');
            $table->string('module', 50)->comment('权限所属模块，例如：人员管理');
            $table->tinyInteger('mount')->default(1)->comment('0：关闭 1：开启');
            $table->timestamp('create_time');
            $table->timestamp('update_time');
            $table->integer('deleted')->unsigned()->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lin_permission');
    }
}
