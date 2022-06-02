<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateActivityLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('activity_log_table', function (Blueprint $table) {
            $table->id('log_id');
            $table->timestamp('log_time');
            $table->unsignedBigInteger('gateway_id');
            $table->unsignedBigInteger('beacon_id');
            $table->bigInteger('rssi');
            $table->float('battery_level');
            $table->float('x_value');
            $table->float('y_value');
            $table->float('z_value');
            $table->char('rawData', 100);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('activity_log');
    }
}
