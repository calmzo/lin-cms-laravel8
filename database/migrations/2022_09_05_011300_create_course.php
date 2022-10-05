<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCourse extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('course', function (Blueprint $table) {
            $table->id();
            $table->string('title', 100)->default('')->comment('标题');
            $table->string('cover', 100)->default('')->comment('封面');
            $table->string('summary', 255)->default('')->comment('简介');
            $table->string('tags', 255)->default('')->comment('标签');
            $table->string('keywords', 100)->default('')->comment('关键字');
            $table->text('details')->comment('详情');
            $table->integer('category_id')->unsigned()->default(0)->comment('分类编号');
            $table->integer('teacher_id')->unsigned()->default(0)->comment('讲师编号');
            $table->decimal('origin_price', 10, 2)->default(0.00)->comment('原始价格');
            $table->decimal('market_price', 10, 2)->default(0.00)->comment('时长价格');
            $table->decimal('vip_price', 10, 2)->default(0.00)->comment('会员价格');
            $table->integer('study_expiry')->unsigned()->default(12)->comment('学习期限');
            $table->integer('refund_expiry')->unsigned()->default(30)->comment('退款期限');
            $table->float('rating', 10, 2)->default(5.00)->comment('用户评分');
            $table->float('score', 10, 2)->default(0.00)->comment('综合评分');
            $table->integer('model')->default(0)->unsigned()->comment('模型');
            $table->integer('level')->default(0)->unsigned()->comment('难度');
            $table->string('attrs', 1000)->default('')->comment('扩展属性');
            $table->integer('featured')->default(0)->comment('推荐标识');
            $table->integer('published')->unsigned()->default(0)->comment('发布标识');
            $table->integer('resource_count')->unsigned()->default(0)->comment('资料数');
            $table->integer('user_count')->unsigned()->default(0)->comment('学员数');
            $table->integer('fake_user_count')->unsigned()->default(0)->comment('虚拟用户数');
            $table->integer('lesson_count')->unsigned()->default(0)->comment('课时数');
            $table->integer('package_count')->unsigned()->default(0)->comment('套餐数');
            $table->integer('review_count')->unsigned()->default(0)->comment('评价数');
            $table->integer('consult_count')->unsigned()->default(0)->comment('咨询数');
            $table->integer('favorite_count')->unsigned()->default(0)->comment('收藏数');
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
        Schema::dropIfExists('course');
    }
}
