<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePackage extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('package', function (Blueprint $table) {
            $table->id();
            $table->string('title', 100)->default('')->comment('标题');
            $table->string('cover', 100)->default('')->comment('封面');
            $table->string('summary')->default('')->comment('摘要');
            $table->decimal('market_price', 10)->default(0.00)->comment('市场价格');
            $table->decimal('vip_price', 10)->default(0.00)->comment('会员价格');
            $table->integer('course_count')->unsigned()->default(0)->comment('课程数量');
            $table->integer('published')->unsigned()->default(0)->comment('发布标识');
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
        Schema::dropIfExists('package');
    }
}
