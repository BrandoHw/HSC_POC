<?php

use Illuminate\Database\Seeder;
use App\TagData;
use App\Reader;
use Carbon\Carbon;
class TagDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Array of Ids for the tag_data
        $tag_ids = array("ac:23:3f:2e:a7:b7",
                        "ac:23:3f:2e:a7:b4",
                        "ac:23:3f:2e:a7:b9",
                        "ac:23:3f:2e:a7:b9",
                        "ac:23:3f:2e:a7:b1");
        $readers = Reader::all();

        for ($i = 0; $i <= 2; $i++){
            $hour = 8 + $i;
            for ($x = 0; $x <= 11; $x++){
                $minute = $x*5;
                $second = rand(0,59);

                for ($j = 23; $j<= 23; $j++){
                    $tag_data = TagData::create([
                        'mac_addr' => 'aa:23:3f:2e:a7:b7',
                        'uuid' => '12345',
                        'rssi' => -89.39,
                        'reader_mac' => 'reader1',
                        'created_at' => Carbon::create(2020, 9, $j, $hour, $minute, $second)
                    ]);
                    
                    //Generate data that will pass check-in/check-out
                    if ($j == 21 || $j == 23){
                        $reader_mac = "reader2";
                    }else {
                        $reader_mac = "reader1";
                    };

                    if ($hour > 12){
                        if ($j == 21 || $j == 24){
                            $reader_mac = "reader2";
                        }else {
                            $reader_mac = "reader1";
                        };
                    }
                    else {$reader_mac = "reader1";};
                    $tag_data = TagData::create([
                        'mac_addr' => 'aa:23:3f:2e:a7:b4',
                        'uuid' => '12345',
                        'rssi' => -89.39,
                        'reader_mac' => $reader_mac,
                        'created_at' => Carbon::create(2020, 9, $j, $hour, $minute, $second),
                    ]);
                
                    $tag_data = TagData::create([
                        'mac_addr' => 'aa:23:3f:2e:a7:b1',
                        'uuid' => '12345',
                        'rssi' => -89.39,
                        'reader_mac' => "reader1",
                        'created_at' => Carbon::create(2020, 9, $j, $hour, $minute, $second),
                    ]);
                }
            }
        }
    }
}
