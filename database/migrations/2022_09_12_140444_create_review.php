<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enums\ReviewEnums;
class CreateReview extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('review', function (Blueprint $table) {
            $table->id();
            $table->integer('course_id')->unsigned()->default(0)->comment('课程id');
            $table->integer('user_id')->unsigned()->default(0)->comment('用户id');
            $table->integer('client_type')->unsigned()->default(0)->comment('终端类型');
            $table->string('client_ip', 64)->default('')->comment('终端IP');
            $table->string('content', 1000)->default('')->comment('内容');
            $table->string('reply', 1000)->default('')->comment('回复');
            $table->float('rating', 10)->unsigned()->default(5.00)->comment('综合评分');
            $table->float('rating1', 10)->unsigned()->default(5.00)->comment('维度1评分');
            $table->float('rating2', 10)->unsigned()->default(5.00)->comment('维度2评分');
            $table->float('rating3', 10)->unsigned()->default(5.00)->comment('维度3评分');
            $table->integer('anonymous')->unsigned()->default(0)->comment('匿名标识');
            $table->enum('published', ReviewEnums::getValues())->default(ReviewEnums::PUBLISH_PENDING)->comment('发布标识');
            $table->integer('like_count')->unsigned()->default(0)->comment('点赞数');
            $table->integer('report_count')->unsigned()->default(0)->comment('举报数');
            $table->timestamp('create_time');
            $table->timestamp('update_time');
            $table->softDeletes('delete_time');
            $table->index('user_id', 'user_id', 'BTREE');
            $table->index('course_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('review');
    }
}
