<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddResidentRoomNum extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('residents_table', function(Blueprint $table){
            $table->unsignedBigInteger('location_room_id')->nullable();
            $table->foreign('location_room_id')->references('location_master_id')->on('locations_master_table')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('residents_table', function($table) {
            $table->dropForeign(['location_room_id']);
            $table->dropColumn('location_room_id');
        });
    }
}
