<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConnect extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('connect', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->unsigned()->default(0)->comment('课程编号');
            $table->string('union_id', 50)->default('')->comment('union_id');
            $table->string('open_id', 50)->default('')->comment('开放ID');
            $table->string('open_name', 30)->default('')->comment('开放名称');
            $table->string('open_avatar', 150)->default('')->comment('开放头像');
            $table->integer('provider')->unsigned()->default(0)->comment('提供方');
            $table->timestamp('create_time');
            $table->timestamp('update_time');
            $table->softDeletes('delete_time');
            $table->index(['union_id', 'provider']);
            $table->index(['open_id', 'provider']);
            $table->index('user_id', 'user_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('connect');
    }
}
