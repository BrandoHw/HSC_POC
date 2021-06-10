<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRolesColorFk extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('roles', function(Blueprint $table){
            $table->unsignedBigInteger('color_id')->nullable();
            $table->softDeletes();
            
            $table->foreign('color_id')->references('color_id')->on('colors_table')->onDelete('cascade');;
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('roles', function($table) {
            $table->dropForeign(['color_id']);
            $table->dropColumn('color_id');
            $table->dropSoftDeletes();
        });
    }
}
