<?php

namespace Database\Seeders;

use App\Resident;
use App\Tag;
use App\UserInfo;
use Illuminate\Database\Seeder;

class BeaconUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //

        // for ($i = 0;$i <= 9;$i++){
        //     $beacon_mac = "AC:23:3F:A2:56:".$i."9";
        //     $this->command->info($beacon_mac);
        //     $current_loc = 1;
        //     if ($i > 4)
        //         $current_loc = 2;
        //     $tags = Tag::create([
        //         'beacon_type' => 1,
        //         'beacon_mac' => $beacon_mac,   
        //         'current_loc' => $current_loc,
        //         'updated_at' => '2021-02-26 15:55:44',
        //     ]);
        // }

        
        $name =[
            "Jay",
            "Jacob",
            "Janet",
            "Alan",
            "Paul",
            "Beka",
            "Jordon",
            "Grace",
            "Manny",
            "Homer",
            "Jean",
            "Gordon",
            "Georgie",
            "Bill",
            "Barry",
            "Cody",
            "Daniel",
            "Edward",
            "Ian",
            "Kevin"
        ];

        $last_name = ["Smith",
                    "Johnson",
                    "Williams",
                    "Brown",
                    "Jones",
                    "Garcia",
                    "Miller",
                    "Davis",
                    "Rodriguez",
                    "Martinez",
                    "Hernandez",
                    "Lopez",
                    "Gonzalez",
                    "Wilson",
                    "Anderson",
                    "Thomas",
                    "Taylor",
                    "Moore",
                    "Jackson",
                    "Martin"];
        for ($i = 0;$i <= 4;$i++){
           $resident = Resident::create([
               'resident_id' => $i+1,
               'beacon_id' => $i+5,
               'resident_fName' => $name[$i],
               'resident_lName' => $last_name[$i],
               'resident_age' => rand(60,100),
               'wheelchair' => rand(0,1),
               'walking_cane' => rand(0,1),
           ]);
        //    $user = UserInfo::create([
        //        'user_id' => $i,
        //         'beacon_id' => $i+10,
        //         'type_id' => 1,
        //         'right_id' => 1,
        //         'fName' => $name[$i+5],
        //         'lName' => $last_name[$i+5],
        //         'phone_number' => '01612321532',
        //     ]);
            
        }
       
   
    }
}
