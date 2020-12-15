<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

class CreateTagData extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tag_data', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->macAddress('mac_addr');
            $table->bigInteger('rssi')->signed();
            $table->macAddress('reader_mac');
            $table->dateTime('created_at');
          
            
            $table->foreign('mac_addr')
                ->references('mac_addr')
                ->on('tags')
                ->onDelete('cascade');

                   
            $table->foreign('reader_mac')
            ->references('mac_addr')
            ->on('readers')
            ->onDelete('cascade');
        });

        /**
         * Name: attendance_trigger:
         * Function: Insert the tag data received from api to tag_data_logs table
         * When: After insert
         */
        DB::unprepared('
        CREATE TRIGGER attendance_trigger
        AFTER INSERT
        ON tag_data FOR EACH ROW

        BEGIN
            INSERT INTO tag_data_logs(mac_addr, rssi, reader_mac_first, first_detected_at, uploaded_at, duration)  
            VALUES (NEW.mac_addr, NEW.rssi, NEW.reader_mac, NEW.created_at, CAST(NEW.created_at AS DATE), "0:00:00")  
            ON DUPLICATE KEY UPDATE   
                reader_mac_last = NEW.reader_mac, last_detected_at= NEW.created_at;
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
        Schema::dropIfExists('tag_data');
    }
}
