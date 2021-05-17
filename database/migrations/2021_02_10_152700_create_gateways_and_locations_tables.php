<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGatewaysAndLocationsTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('gateways_table');
        Schema::dropIfExists('locations_master_table');
        Schema::dropIfExists('locations_type_table');

        Schema::create('locations_type_table', function (Blueprint $table) {
            $table->bigIncrements('type_id');
            $table->string('location_type', 45);
        });

        Schema::create('locations_master_table', function (Blueprint $table) {
            $table->bigIncrements('location_master_id');
            $table->bigInteger('location_type_id')->unsigned()->default(null);
            $table->bigInteger('floor')->unsigned()->default(null);
            $table->string('location_description', 255)->default(null);
            $table->foreign('location_type_id')
                ->references('type_id')
                ->on('locations_type_table')
                ->onDelete('cascade');

            $table->foreign('floor')
            ->references('id')
            ->on('floors')
            ->onDelete('cascade');
        });

        Schema::create('gateways_table', function (Blueprint $table) {
            $table->bigIncrements('gateway_id');
            $table->string('serial')->unique()->default(null);
            $table->macAddress('mac_addr')->unique()->default(null);
            $table->ipAddress('reader_ip')->default(null)->nullable();
            $table->bigInteger('location_id')->unsigned()->default(null)->nullable();
            $table->tinyInteger('reader_status')->default(null)->nullable();
            $table->boolean('assigned')->default(false);

            $table->timestamps();

            $table->foreign('location_id')
                ->references('location_master_id')
                ->on('locations_master_table')
                ->onDelete('cascade');
        });

        Schema::create('gateway_zones', function (Blueprint $table) {
            $table->id();
            $table->json('geoJson');
            $table->macAddress('mac_addr');
            $table->string('location');
            $table->timestamps();

            $table->foreign('mac_addr')
                ->references('mac_addr')
                ->on('gateways_table')
                ->onDelete('cascade');

            $table->string('image_id')->nullable();
        });

            DB::unprepared(' 
            CREATE TRIGGER assign_reader
                AFTER INSERT
                ON gateway_zones FOR EACH ROW
                BEGIN
                    UPDATE gateways_table
                    SET assigned = true
                    WHERE mac_addr = NEW.mac_addr;
               END
          ');
  
          DB::unprepared(' 
            CREATE TRIGGER unassign_reader
                AFTER DELETE
                ON gateway_zones FOR EACH ROW
                BEGIN
                    UPDATE gateways_table
                    SET assigned = false
                    WHERE mac_addr = OLD.mac_addr;
                END
          ');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('gateways_table');
        Schema::dropIfExists('locations_master_table');
        Schema::dropIfExists('locations_type_table');
    }
}
