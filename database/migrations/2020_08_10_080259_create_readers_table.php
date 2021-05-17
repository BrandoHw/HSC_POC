<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReadersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('readers');
        Schema::create('readers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('serial')->unique();
            $table->macAddress('mac_addr')->unique();
            $table->unsignedBigInteger('floor_id')->nullable();
            $table->boolean('assigned')->default(false);
            $table->timestamps();

            $table->foreign('floor_id')
                ->references('id')
                ->on('floors')
                ->onDelete('cascade');
        });

        // Schema::dropIfExists('gateways_table');
        // Schema::create('gateways_table', function (Blueprint $table) {
        //     $table->bigIncrements('gateway_id');
        //     $table->string('serial')->unique()->default(null);
        //     $table->macAddress('mac_addr')->unique()->default(null);
        //     $table->ipAddress('reader_ip')->default(null);
        //     $table->bigInteger('location_id')->unsigned()->default(null);
        //     $table->tinyInteger(4, false, false)->default(null);
        //     $table->boolean('assigned')->default(false);

        //     $table->timestamps();

        //     $table->foreign('location_id')
        //         ->references('location_master_id')
        //         ->on('locations_master_table')
        //         ->onDelete('cascade');
        // });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('readers');
    }
}
