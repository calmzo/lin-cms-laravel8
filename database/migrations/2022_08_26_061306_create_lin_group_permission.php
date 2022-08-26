<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLinGroupPermission extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lin_group_permission', function (Blueprint $table) {
            $table->id();
            $table->integer('group_id')->default(1)->comment('分组id');
            $table->integer('permission_id')->default(1)->comment('权限id');
            $table->index(['group_id', 'permission_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lin_group_permission');
    }
}
