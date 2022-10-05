<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTask extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('task', function (Blueprint $table) {
            $table->id();
            $table->integer('item_id')->default(0)->comment('条目id');
            $table->integer('item_type')->default(0)->comment('条目类型 1=发货 2=退款');
            $table->string('item_info', 3000)->default('')->comment('条目内容');
            $table->integer('status')->default(1)->comment('状态 1=待定 2=完成 3=取消 4=失败');
            $table->integer('priority')->default(30)->comment('优先级 10=高 20=中 30=低');
            $table->integer('try_count')->default(0)->comment('重试数');
            $table->integer('max_try_count')->default(3)->comment('最大重试数');
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
        Schema::dropIfExists('task');
    }
}
