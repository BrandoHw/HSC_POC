<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;


class ScheduleReaderGroups extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //

        DB::table('groups')->insertGetId([
            'name' => Str::random(10),
            'detail' => Str::random(10),
            "created_at" =>  \Carbon\Carbon::now(), # new \Datetime()
            "updated_at" => \Carbon\Carbon::now(),
        ]);

        DB::table('groups')->insertGetId([
            'name' => Str::random(10),
            'detail' => Str::random(10),
            "created_at" =>  \Carbon\Carbon::now(), # new \Datetime()
            "updated_at" => \Carbon\Carbon::now(),
        ]);


        DB::table('schedules')->insertGetId([
            "name" => "Schedule for Sunway Group",
            "created_at" =>  \Carbon\Carbon::now(), # new \Datetime()
            "updated_at" => \Carbon\Carbon::now(),
            "group_id" => 1,
        ]);

        DB::table('schedules')->insertGetId([
            "name" => "Schedule for Damansara Group",
            "created_at" =>  \Carbon\Carbon::now(), # new \Datetime()
            "updated_at" => \Carbon\Carbon::now(),
            "group_id" => 2,
        ]);
        
        DB::table('readers')->insertGetId([
            "uuid" =>  "00112233445566778899ABCDE0123456",
            "mac_add" => "00:11:22:33:44:55",
            "location" => "Sunway Pinnacle MDT Offices 15F",
            "created_at" =>  \Carbon\Carbon::now(), # new \Datetime()
            "updated_at" => \Carbon\Carbon::now(),
        ]);

        
        DB::table('readers')->insertGetId([
            "uuid" =>  "22222233445566778899ABCDE0123456",
            "mac_add" => "22:22:22:33:44:55",
            "location" => "Sunway Pinnacle MDT Offices 19F",
            "created_at" =>  \Carbon\Carbon::now(), # new \Datetime()
            "updated_at" => \Carbon\Carbon::now(),
        ]);



        DB::table('timeblocks')->insertGetId([
            "start_time" =>  "10:00:00",
            "end_time" => "12:00:00",
            "day"=> 1,
            "location" => "Sunway Pinnacle MDT Offices 15F",
            "schedule_id" => 1,
            "reader_id" =>  1,
        ]);

        DB::table('timeblocks')->insertGetId([
            "start_time" =>  "14:00:00",
            "end_time" => "15:00:00",
            "day" => 1,
            "location" => "Sunway Pinnacle MDT Offices 19F",
            "schedule_id" => 1,
            "reader_id" =>  2,
        ]);


        DB::table('timeblocks')->insertGetId([
            "start_time" =>  "10:00:00",
            "end_time" => "12:00:00",
            "day"=> 2,
            "location" => "Sunway Pinnacle MDT Offices 15F",
            "schedule_id" => 1,
            "reader_id" =>  1,
        ]);

        
        DB::table('timeblocks')->insertGetId([
            "start_time" =>  "12:00:00",
            "end_time" => "14:00:00",
            "day"=> 2,
            "location" => "Sunway Pinnacle MDT Offices 19F",
            "schedule_id" => 1,
            "reader_id" =>  2,
        ]);

        DB::table('timeblocks')->insertGetId([
            "start_time" =>  "14:00:00",
            "end_time" => "17:00:00",
            "day" => 2,
            "location" => "Sunway Pinnacle MDT Offices 15F",
            "schedule_id" => 1,
            "reader_id" =>  1,
        ]);

    }


}
