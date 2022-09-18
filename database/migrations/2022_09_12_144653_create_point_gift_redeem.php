<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePointGiftRedeem extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('point_gift_redeem', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->unsigned()->default(0)->comment('用户id');
            $table->string('user_name', 30)->default('')->comment('用户名称');
            $table->integer('gift_id')->unsigned()->default(0)->comment('礼品id');
            $table->string('gift_name', 100)->default('')->comment('礼品名称');
            $table->integer('gift_type')->unsigned()->default(0)->comment('礼品类型');
            $table->integer('gift_point')->unsigned()->default(0)->comment('礼品积分');
            $table->string('contact_name', 30)->default('')->comment('联系人');
            $table->string('contact_phone', 30)->default('')->comment('联系电话');
            $table->string('contact_address', 100)->default('')->comment('联系地址');
            $table->string('remark')->default('')->comment('备注');
            $table->integer('status')->unsigned()->default(0)->comment('状态标识');
            $table->timestamp('create_time');
            $table->timestamp('update_time');
            $table->softDeletes('delete_time');
            $table->index('gift_id');
            $table->index('user_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('point_gift_redeem');
    }
}
