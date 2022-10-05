<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserBalance extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_balance', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->unsigned()->default(0)->comment('用户id');
            $table->decimal('cash', 10)->default(0.00)->comment('可用现金');
            $table->integer('point')->default(0)->comment('可用积分');
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
        Schema::dropIfExists('user_balance');
    }
}
