<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStaffTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('staff_table', function (Blueprint $table) {
            $table->bigIncrements('staff_id');
            $table->string('name');
            $table->string('email')->unique()->default(null)->nullable();
            $table->string('phone_num')->default(null)->nullable();
            $table->string('job_title')->default(null)->nullable();

            $table->bigInteger('beacon_id')->unsigned()->default(null)->nullable();

            $table->foreign('beacon_id')
                ->references('beacon_id')
                ->on('beacons_table');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('staff_table');
    }
}
