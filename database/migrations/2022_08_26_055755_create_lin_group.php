<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLinGroup extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lin_group', function (Blueprint $table) {
            $table->id();
            $table->string('name', 60)->comment('名称');
            $table->string('info', 50)->comment('描述');
            $table->tinyInteger('level')->default(3)->comment('分组级别 1：root 2：guest 3：user（root、guest分组只能存在一个)');
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
        Schema::dropIfExists('lin_group');
    }
}
