<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLinLog extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lin_log', function (Blueprint $table) {
            $table->id();
            $table->string('message', 450);
            $table->integer('user_id')->default(0);
            $table->string('username', 20);
            $table->integer('status_code')->default(0);
            $table->string('method', 20);
            $table->string('path', 50);
            $table->string('permission', 100)->nullable();
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
        Schema::dropIfExists('lin_log');
    }
}
