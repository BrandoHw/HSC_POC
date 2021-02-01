<?php

use Illuminate\Database\MigratiONs\MigratiON;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTagDataLogsTable extENDs MigratiON
{
    /**
     * Run the migratiONs.
     *
     * @return void
     */
    public functiON up()
    {
        Schema::create('tag_data_logs', functiON (Blueprint $table) {
            $table->bigIncrements('id');
            $table->macAddress('mac_addr');
            $table->bigInteger('rssi')->signed();
            $table->macAddress('reader_mac_first');
            $table->dateTime('first_detected_at');
            $table->integer('check_in_status')->nullable();
            $table->macAddress('reader_mac_last')->nullable();
            $table->dateTime('last_detected_at')->nullable();
            $table->integer('check_out_status')->nullable();
            $table->unsignedBigInteger('building_id');
            $table->date('uploaded_at')->useCurrent();
            $table->time('duration')->nullable();
            $table->unique(['mac_addr', 'building_id', 'uploaded_at'], "mac_building_date");
        });

        /**
         * Name: get_building_info_trigger:
         * Function: Get the building id of the first detected readers
         * When: Before insert
         */
        DB::unprepared('
        CREATE TRIGGER get_building_info_trigger
        BEFORE INSERT
        ON tag_data_logs FOR EACH ROW

        BEGIN
            SELECT buildings.id
            FROM readers 
            INNER JOIN floors ON floors.id = readers.floor_id 
            INNER JOIN buildings ON buildings.id = floors.building_id
            WHERE readers.mac_addr = NEW.reader_mac_first LIMIT 1 INTO @building_id;
	        
            SET NEW.building_id = @building_id;
        END
        ');

        /**
         * Name: check_in_trigger:
         * Function: Check the first detected time status according to the schedule of the user
         * When: Before insert
         * Return: 
         *  -5: Wrong location/ User not assigned a schedule
         *  -1: Early
         *   0: On-time (+/- 5 mins)
         *   1: Late
         */
        DB::unprepared('
        CREATE TRIGGER check_in_trigger
        BEFORE INSERT
        ON tag_data_logs FOR EACH ROW

        BEGIN
            SELECT timeblocks.start_time
            FROM tags
            INNER JOIN users ON users.id = tags.user_id 
            INNER JOIN `groups` ON groups.id = users.group_id
            INNER JOIN schedules ON groups.id = schedules.group_id 
            INNER JOIN timeblocks ON schedules.id = timeblocks.schedule_id 
            INNER JOIN buildings ON buildings.id = timeblocks.building_id
            WHERE tags.mac_addr = NEW.mac_addr
            AND timeblocks.day = DAYOFWEEK(CAST(NEW.first_detected_at AS DATE))
            AND buildings.id = NEW.building_id
            LIMIT 1 INTO @start_time;

            SELECT CASE 
            WHEN @start_time IS NULL THEN -5
            WHEN CAST(NEW.first_detected_at AS TIME) BETWEEN SUBTIME(@start_time, "0:05:00") AND ADDTIME(@start_time, "0:05:00") THEN 0
            WHEN CAST(NEW.first_detected_at AS TIME) > @start_time THEN 1
            ELSE -1
            END INTO @check_in_status;

            SET NEW.check_in_status = @check_in_status;
        END
        ');

        /**
         * Name: check_out_trigger:
         * Function: Check the last detected time status according to the schedule of the user
         * When: Before update
         * Return: 
         *  -5: Wrong location/ User not assigned a schedule
         *  -1: Early
         *   0: On-time (+/- 5 mins)
         *   1: Late
         */
        DB::unprepared('
        CREATE TRIGGER check_out_trigger
        BEFORE UPDATE
        ON tag_data_logs FOR EACH ROW

        BEGIN
            SELECT timeblocks.end_time
            FROM tags
            INNER JOIN users ON users.id = tags.user_id 
            INNER JOIN `groups` ON groups.id = users.group_id
            INNER JOIN schedules ON groups.id = schedules.group_id 
            INNER JOIN timeblocks ON schedules.id = timeblocks.schedule_id 
            INNER JOIN buildings ON buildings.id = timeblocks.building_id
            WHERE tags.mac_addr = NEW.mac_addr
            AND timeblocks.day = DAYOFWEEK(CAST(NEW.last_detected_at AS DATE))
            AND buildings.id = NEW.building_id
            LIMIT 1 INTO @end_time;

            SELECT CASE 
            WHEN @end_time IS NULL THEN -5
            WHEN CAST(NEW.last_detected_at AS TIME) BETWEEN SUBTIME(@end_time, "0:05:00") AND ADDTIME(@start_time, "0:05:00") THEN 0
            WHEN CAST(NEW.last_detected_at AS TIME) > @end_time THEN 1
            ELSE -1
            END INTO @check_out_status;

            SET NEW.check_out_status = @check_out_status;
        END
        ');

        /**
         * Name: calculate_duration_trigger:
         * Function: Calculate the total duration spent in that location
         * PS: If the user leave the location more than 0:01:15, this time interval wont be counted
         * When: Before update
         */
        DB::unprepared('
        CREATE TRIGGER calculate_duration_trigger
        BEFORE UPDATE
        ON tag_data_logs FOR EACH ROW

        BEGIN
	        SELECT CASE 
	        WHEN TIMEDIFF(NEW.last_detected_at, OLD.last_detected_at) <= "0:01:15" THEN TIMEDIFF(NEW.last_detected_at, OLD.last_detected_at)
	        WHEN TIMEDIFF(NEW.last_detected_at, OLD.last_detected_at) IS NULL THEN TIMEDIFF(NEW.last_detected_at, NEW.first_detected_at)
	        ELSE "0:00:00"
	        END INTO @add_time;
	        
            SET NEW.duration = ADDTIME(OLD.duration, @add_time);
        END
        ');
    }

    /**
     * Reverse the migratiONs.
     *
     * @return void
     */
    public functiON down()
    {
        Schema::dropIfExists('tag_data_logs');
    }
}
