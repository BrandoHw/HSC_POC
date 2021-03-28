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
        Schema::dropIfExists('gateways_table2');
        Schema::dropIfExists('locations_master_table2');
        Schema::dropIfExists('locations_type_table2');

        Schema::create('locations_type_table2', function (Blueprint $table) {
            $table->bigIncrements('type_id');
            $table->string('location_type', 45);
        });

        Schema::create('locations_master_table2', function (Blueprint $table) {
            $table->bigIncrements('location_master_id');
            $table->bigInteger('location_type_id')->unsigned()->default(null);
            $table->bigInteger('floor')->unsigned()->default(null);
            $table->string('location_description', 255)->default(null);
            $table->foreign('location_type_id')
                ->references('type_id')
                ->on('locations_type_table2')
                ->onDelete('cascade');

            $table->foreign('floor')
            ->references('id')
            ->on('floors')
            ->onDelete('cascade');
        });

        Schema::create('gateways_table2', function (Blueprint $table) {
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
                ->on('locations_master_table2')
                ->onDelete('cascade');
        });

        Schema::create('gateway_zones2', function (Blueprint $table) {
            $table->id();
            $table->json('geoJson');
            $table->macAddress('mac_addr');
            $table->string('location');
            $table->timestamps();

            $table->foreign('mac_addr')
                ->references('mac_addr')
                ->on('gateways_table2')
                ->onDelete('cascade');

            $table->string('image_id')->nullable();
        });

            DB::unprepared(' 
            CREATE TRIGGER assign_reader2
                AFTER INSERT
                ON gateway_zones2 FOR EACH ROW
                BEGIN
                    UPDATE gateways_table2
                    SET assigned = true
                    WHERE mac_addr = NEW.mac_addr;
               END
          ');
  
          DB::unprepared(' 
            CREATE TRIGGER unassign_reader2
                AFTER DELETE
                ON gateway_zones2 FOR EACH ROW
                BEGIN
                    UPDATE gateways_table2
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
        Schema::dropIfExists('gateways_table2');
        Schema::dropIfExists('locations_master_table2');
        Schema::dropIfExists('locations_type_table2');
    }
}
