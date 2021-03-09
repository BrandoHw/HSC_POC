<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGatewayZonesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gateway_zones', function (Blueprint $table) {
            $table->id();
            $table->json('geoJson');
            $table->macAddress('mac_addr');
            $table->string('location');
            $table->timestamps();

            $table->foreign('mac_addr')
                ->references('mac_addr')
                ->on('readers')
                //->on('gateways_table)
                ->onDelete('cascade');

            $table->string('image_id')->nullable();
        });

            DB::unprepared(' 
            CREATE TRIGGER assign_gateway
                AFTER INSERT
                ON gateway_zones FOR EACH ROW
                BEGIN
                    UPDATE gateways_table
                    SET assigned = true
                    WHERE mac_addr = NEW.mac_addr;
               END
          ');
  
          DB::unprepared(' 
            CREATE TRIGGER unassign_gateway
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
        Schema::dropIfExists('gateway_zones');
    }
}
