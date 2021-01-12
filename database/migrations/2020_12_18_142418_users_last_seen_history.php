<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UsersLastSeenHistory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('users_last_seen_history', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('user_id');
            $table->bigInteger('rssi')->signed();
            $table->macAddress('reader_mac');
            $table->macAddress('tag_mac');
            $table->foreignId('reader_id');

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

            $table->foreign('reader_id')
            ->references('id')
            ->on('readers')
            ->onDelete('cascade');
        });


        DB::unprepared('
        CREATE TRIGGER user_last_seen_history_trigger
            AFTER UPDATE
            ON users_last_seen FOR EACH ROW
    
            BEGIN
            INSERT INTO users_last_seen_history(created_at, updated_at, user_id, rssi, reader_mac, tag_mac, reader_id)  
                SELECT NEW.created_at,
                NEW.updated_at,
                NEW.user_id, 
                NEW.rssi, 
                NEW.reader_mac, 
                NEW.tag_mac,
                r.id
                FROM readers r
                WHERE r.mac_addr = NEW.reader_mac;
        END
        ');
        // DB::unprepared('
        // CREATE TRIGGER user_last_seen_history_trigger
        //     AFTER UPDATE
        //     ON users_last_seen FOR EACH ROW
      
        //     BEGIN
        //         INSERT INTO users_last_seen_history(created_at, updated_at, user_id, rssi, reader_mac, tag_mac)  
        //         VALUES(NEW.created_at, NEW.updated_at, NEW.user_id, NEW.rssi, NEW.reader_mac, NEW.tag_mac);
        //     END
        // ');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::dropIfExists('users_last_seen_history');
    }
}
