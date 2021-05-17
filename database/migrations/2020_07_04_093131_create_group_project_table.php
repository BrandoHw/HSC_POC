<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGroupProjectTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Schema::create('group_project', function (Blueprint $table) {
        //     $table->unsignedBigInteger('project_id');
        //     $table->unsignedBigInteger('group_id');
            
        //     $table->foreign('project_id')
        //         ->references('id')
        //         ->on('projects')
        //         ->onDelete('cascade');

        //     $table->foreign('group_id')
        //         ->references('id')
        //         ->on('groups')
        //         ->onDelete('cascade');

        //     $table->primary(['project_id', 'group_id'], 'group_project_project_id_group_id_primary');
        // });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('group_project');
    }
}
