<?php

namespace Database\Seeders;

use App\Reader;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory;
use App\User;
use App\Tag;
use App\UserLastSeen;
use Illuminate\Support\Carbon;

class UserLastSeenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $mac_array = [
        'BC:23:3F:C0:80:6A',
        'BC:23:3F:C0:80:70',
        'BC:23:3F:C0:80:80',
        'BC:23:3F:C0:80:64',
        'BC:23:3F:C0:80:69',
        'BC:23:3F:C0:80:6D',
        'BC:23:3F:C0:80:67',
        'BC:23:3F:C0:80:28',
        'BC:23:3F:C0:80:33',
        'BC:23:3F:C0:80:22',
        'CC:23:3F:C0:80:6A',
        'CC:23:3F:C0:80:70',
        'CC:23:3F:C0:80:80',
        'CC:23:3F:C0:80:64',
        'CC:23:3F:C0:80:69',
        'CC:23:3F:C0:80:6D',
        'CC:23:3F:C0:80:67',
        'CC:23:3F:C0:80:28',
        'CC:23:3F:C0:80:33',
        'CC:23:3F:C0:80:22'
        ];

        $tag_array = [
            'BC:42:3F:C0:80:6A',
            'BC:42:3F:C0:80:70',
            'BC:42:3F:C0:80:80',
            'BC:42:3F:C0:80:64',
            'BC:42:3F:C0:80:69',
            'BC:42:3F:C0:80:6D',
            'BC:42:3F:C0:80:67',
            'BC:42:3F:C0:80:28',
            'BC:42:3F:C0:80:33',
            'BC:42:3F:C0:80:22',
            'CC:42:3F:C0:80:6A',
            'CC:42:3F:C0:80:70',
            'CC:42:3F:C0:80:80',
            'CC:42:3F:C0:80:64',
            'CC:42:3F:C0:80:69',
            'CC:42:3F:C0:80:6D',
            'CC:42:3F:C0:80:67',
            'CC:42:3F:C0:80:28',
            'CC:42:3F:C0:80:33',
            'CC:42:3F:C0:80:22'
            ];

        $user_array = [
            8,
            9,
            10,
            11,
            12
        ];

        $names =[
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

         /* User Creation */
         for ($i=1;$i<20;$i++){
            $users[$i] = User::create([
                'name' => $names[$i]." ".$last_name[$i],
                'username' => $names[$i],
                'email' => $names[$i].'@123.com',
                'password' => bcrypt($names[$i].'@123')
            ]);

            $tags[$i] = Tag::create([
                'serial' => 'T000000'.($i+6),
                'mac_addr' => $tag_array[$i]
            ]);

            Reader::create([
                'serial' => 'R000000'.($i+5),
                'mac_addr' => $mac_array[$i]   
            ]);

            $usersLastSeen[$i] = UserLastSeen::create([
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'rssi' => rand(-60, 15),
                'tag_mac' => $tag_array[$i],
                'reader_mac' => $mac_array[$i]   
            ]);
         }
  
  
        foreach ($users as $user){
            /* Add user to this group */
            //$group->users()->save($user);
            // $this->command->info('User saved into Group!');

            /* Assign default role to this user */
            $user->assignRole('Default');
            // $this->command->info('User assigned Role!');
        }

        for($i = 1; $i < 20; $i++){
            /* Assign tag to user */
            $users[$i]->tag()->save($tags[$i]);
            // $this->command->info('Tag '.($i + 1).' saved into '.($i + 1).'!');
            $users[$i]->lastSeen()->save($usersLastSeen[$i]);
        }

    }
}
