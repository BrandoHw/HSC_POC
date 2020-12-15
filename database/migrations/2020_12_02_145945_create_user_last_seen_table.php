<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserLastSeenTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users_last_seen', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('user_id');
            $table->bigInteger('rssi')->signed();
            $table->macAddress('reader_mac');
            $table->macAddress('tag_mac')->unique();

            $table->foreign('user_id')
            ->references('id')
            ->on('users')
            ->onDelete('cascade');

            $table->foreign('tag_mac')
                ->references('mac_addr')
                ->on('tags')
                ->onDelete('cascade');
  
            $table->foreign('reader_mac')
            ->references('mac_addr')
            ->on('readers')
            ->onDelete('cascade');
        });

        /**
         * Name: user_last_seen_trigger:
         * Function: Update the latest location of the user in the user_last_seen table
         * When: After insert
         */
        DB::unprepared('
        CREATE TRIGGER user_last_seen_trigger
            AFTER INSERT
            ON tag_data FOR EACH ROW
      
            BEGIN
                INSERT INTO users_last_seen(created_at, updated_at, user_id, rssi, reader_mac, tag_mac)  
                SELECT NEW.created_at,
                    NEW.created_at,
                    t.user_id,
                    NEW.rssi,
                    NEW.reader_mac,
                    NEW.mac_addr
                    FROM tags t
                    WHERE t.mac_addr = NEW.mac_addr
                ON DUPLICATE KEY UPDATE  
                    users_last_seen.updated_at = IF(@condition:=((users_last_seen.updated_at + INTERVAL 15 second) < NEW.created_at OR NEW.rssi > rssi), NEW.created_at, users_last_seen.created_at),
                    rssi =  IF(@condition, NEW.rssi, rssi),
                    users_last_seen.reader_mac =  IF(@condition, NEW.reader_mac, users_last_seen.reader_mac),
                    users_last_seen.user_id =  IF(@condition, t.user_id,  users_last_seen.user_id);
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
        Schema::dropIfExists('user_last_seen');
    }
}
