<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLinUser extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lin_user', function (Blueprint $table) {
            $table->id();
            $table->string('username', 24)->comment('用户名，唯一');
            $table->string('nickname', 24)->comment('昵称');
            $table->string('password', 500);
            $table->string('email', 100)->comment('邮箱');
            $table->string('avatar', 500)->comment('头像url');
            $table->timestamp('create_time');
            $table->timestamp('update_time');
            $table->integer('deleted')->unsigned()->default(0);
            $table->index(['nickname', 'email']);
            $table->unique(['username', 'email']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lin_user');
    }
}
