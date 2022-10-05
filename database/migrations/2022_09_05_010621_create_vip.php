<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVip extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vip', function (Blueprint $table) {
            $table->id();
            $table->string('title', 30)->default('')->comment('标题');
            $table->string('cover', 100)->default('')->comment('封面');
            $table->integer('expiry')->default(0)->comment('有效期');
            $table->decimal('price', 10, 2)->default(0.00)->comment('价格');
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
        Schema::dropIfExists('vip');
    }
}
