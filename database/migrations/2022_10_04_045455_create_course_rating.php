<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCourseRating extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('course_rating', function (Blueprint $table) {
            $table->integer('course_id')->primary()->comment('主键编号');
            $table->float('rating', 10)->unsigned()->default(5.00)->comment('综合评分');
            $table->float('rating1', 10)->unsigned()->default(5.00)->comment('维度1评分');
            $table->float('rating2', 10)->unsigned()->default(5.00)->comment('维度2评分');
            $table->float('rating3', 10)->unsigned()->default(5.00)->comment('维度3评分');
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
        Schema::dropIfExists('course_rating');
    }
}
