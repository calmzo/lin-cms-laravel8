<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrganizationRelations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('organization_relations', function (Blueprint $table) {
            $table->integer('root_id')->default(0)->comment('根节点');
            $table->integer('node_id')->default(0)->comment('子节点');
            $table->integer('depth')->default(0)->comment('节点深度');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('organization_relations');
    }
}
