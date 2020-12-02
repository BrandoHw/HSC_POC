<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use App\Building;
use App\Company;
use App\Floor;
use App\Group;
use App\Project;
use App\Schedule;
use App\Reader;
use App\Tag;
use App\Timeblocks;
use App\User;
use Carbon\Carbon;

class PopulateDataForTesting extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /**
         * Add 1 company 
         * Add 2 building
         * Add 2 floor
         * Add 2 readers
         * 
         * Assign this building to this company
         * Assign 1 floor to each building
         * Assign 1 reader to each floor
         */

        /* Company creation */
        $company = Company::create([
            'name' => 'MDT Innovations Sdn Bhd'             
        ]);
        // $this->command->info('Company created!');

        /* Building creation */
        $buildings[0] = Building::create([
            'name' => 'Main Office',
            'floor_num' => 2,
            'address' => 'MDT Innovations Sdn Bhd, Bandar Sunway, Petaling Jaya, Selangor, Malaysia',
            'lat' => 3.07,
            'lng' => 101.61                 
        ]);
        // $this->command->info('Building 1 created!');

        $buildings[1] = Building::create([
            'name' => 'RnD Office',
            'floor_num' => 2,
            'address' => 'MDT Research and Manufacturing Sdn. Bhd., Jalan Mj 2, Taman Merdeka Jaya, Melaka, Malacca, Malaysia',
            'lat' => 2.27,
            'lng' => 102.24             
        ]);
        // $this->command->info('Building 2 created!');

        /* Floor creation */
        $floors[0] = Floor::create([
            'number' => 1                       
        ]);
        // $this->command->info('Floor 1 created!');

        $floors[1] = Floor::create([
            'number' => 1                       
        ]);
        // $this->command->info('Floor 2 created!');
        
        $floors[2] = Floor::create([
            'number' => 2                       
        ]);
        // $this->command->info('Floor 1 created!');
        
        $floors[3] = Floor::create([
            'number' => 2                       
        ]);
        // $this->command->info('Floor 2 created!');

        /* Reader creation */
        $readers[0] = Reader::create([
            'serial' => 'R0000001',
            'mac_addr' => 'AC:23:3F:C0:80:6A'                   
        ]);
        // $this->command->info('Reader 1 created!');

        $readers[1] = Reader::create([
            'serial' => 'R0000002',
            'mac_addr' => 'AC:23:3F:C0:80:80'                    
        ]);
        // $this->command->info('Reader 2 created!');
        
        $readers[2] = Reader::create([
            'serial' => 'R0000003',
            'mac_addr' => 'AC:23:3F:C0:80:70'                    
        ]);
        // $this->command->info('Reader 3 created!');

        $readers[3] = Reader::create([
            'serial' => 'R0000004',
            'mac_addr' => 'AC:23:3F:C0:80:64'                    
        ]);
        // $this->command->info('Reader 4 created!');

        for($i = 0; $i < 2; $i++){
            /* Assign building to company */
            $company->buildings()->save($buildings[$i]);
            // $this->command->info('Building '.($i + 1).' saved into Company!');

            /* Assign floor to building */
            $buildings[$i]->floors()->save($floors[$i]);
            // $this->command->info('Floor '.($i + 1).' saved into Building'.($i + 1).'!');
            
            $buildings[$i]->floors()->save($floors[$i + 2]);
            // $this->command->info('Floor '.($i + 2).' saved into Building'.($i + 1).'!');
        }
        
        for($i = 0; $i < 4; $i++){
            /* Assign reader to floor */
            $floors[$i]->readers()->save($readers[$i]);
            // $this->command->info('Reader '.($i + 1).' saved into Floor'.($i + 1).'!');
        }

        /**
         * Add 1 project
         * Add 1 group
         * 
         * Add 1 schedule
         * Add 5 timeblocks with morning shift
         * Add 5 timeblocks with afternoon shift
         * Assign this schedule to this group
         * Assign these timeblocks to this schedule
         * Assign the same company to these timeblocks
         * Assign morning shift timeblock with main office
         * Assign afternoon shift timeblock with RnD office
         * 
         * Add 4 testing tags
         * Add 4 default users
         * 
         * Assign one project to this group
         * Assign each user with a tag
         * Assign these users to this group
         */

        /* Project Creation */
        $project = Project::create([
            'name' => 'Apollo',
            'start_date' => Carbon::createFromDate(2020, 1, 1, 'Asia/Kuala_Lumpur'),
            'end_date' => Carbon::createFromDate(2020, 12, 31, 'Asia/Kuala_Lumpur'),
        ]);
        // $this->command->info('Project created!');

        /* Group Creation */
        $group = Group::create([
            'name' => 'Beta',          
        ]);
        // $this->command->info('Group created!');

        /* Schedule Creation */
        $schedule = Schedule::create([]);
        // $this->command->info('Schedule created!');

        /* Assign schedule to this group */
        $group->schedule()->save($schedule);

        // Create timeblocks from monday to friday
        for($i = 2; $i <= 6; $i++){
            // Morning shift
            $timeblocks[0] = Timeblocks::create([
                'start_time' => Carbon::createFromTime(9, 0, 0, 'Asia/Kuala_Lumpur'),
                'end_time' => Carbon::createFromTime(12, 0, 0, 'Asia/Kuala_Lumpur'),
                'day' => $i           
            ]);
            // $this->command->info($i.': Timeblock morning created!');

            // Afternoon shift
            $timeblocks[1] = Timeblocks::create([
                'start_time' => Carbon::createFromTime(13, 0, 0, 'Asia/Kuala_Lumpur'),
                'end_time' => Carbon::createFromTime(17, 0, 0, 'Asia/Kuala_Lumpur'),
                'day' => $i       
            ]);
            // $this->command->info($i.': Timeblock afternoon created!');
            
            for($j = 0; $j < 2; $j++){
                /* Assign timeblock to schedule */
                $schedule->timeblocks()->save($timeblocks[$j]);
                // $this->command->info($i.': Timeblock '.($j+1).' created!');

                /* Assign company to timeblock */
                $company->timeblocks()->save($timeblocks[$j]);
                // $this->command->info($i.': Timeblock '.($j+1).' saved into Company!');
                
                /* Assign building to timeblock */
                $buildings[$j]->timeblocks()->save($timeblocks[$j]);
                // $this->command->info($i.': Timeblock '.($j+1).' saved into Building '.($j+1).'!');
            }

        }

        /* User Creation */
        $users[0] = User::create([
            'name' => 'John',
            'username' => 'john',
            'email' => 'john@123.com',
            'password' => bcrypt('john@123')
        ]);
        // $this->command->info('User 1 created!');

        $users[1] = User::create([
            'name' => 'Alice',
            'username' => 'alice',
            'email' => 'alice@123.com',
            'password' => bcrypt('alice@123')
        ]);
        // $this->command->info('User 2 created!');
        
        $users[2] = User::create([
            'name' => 'Edwin',
            'username' => 'edwin',
            'email' => 'edwin@123.com',
            'password' => bcrypt('edwin@123')
        ]);
        // $this->command->info('User 3 created!');
        
        $users[3] = User::create([
            'name' => 'Mark',
            'username' => 'mark',
            'email' => 'mark@123.com',
            'password' => bcrypt('mark@123')
        ]);
        // $this->command->info('User 4 created!');

        $users[4] = User::create([
            'name' => 'Jim',
            'username' => 'jim',
            'email' => 'jim@123.com',
            'password' => bcrypt('jim@123')
        ]);
        // $this->command->info('User 5 created!');

        /* Tag Creation */

        /* For testing in MDT*/
        // AC233F2EA7AE
        // AC233F2EA7BF
        // AC233F2EA7BD
        // AC233F2EA7B8
        // AC233F2EA7B7

        $tags[0] = Tag::create([
            'serial' => 'T0000001',
            'mac_addr' => 'AC:23:3F:2E:A7:AE',           
        ]);
        // $this->command->info('Tag 1 created!');

        $tags[1] = Tag::create([
            'serial' => 'T0000002',
            'mac_addr' => 'AC:23:3F:2E:A7:BF'            
        ]);
        // $this->command->info('Tag 2 created!');

        $tags[2] = Tag::create([
            'serial' => 'T0000003',
            'mac_addr' => 'AC:23:3F:2E:A7:BD'            
        ]);
        // $this->command->info('Tag 3 created!');

        $tags[3] = Tag::create([
            'serial' => 'T0000004',
            'mac_addr' => 'AC:23:3F:2E:A7:B8'           
        ]);
        // $this->command->info('Tag 4 created!');

        $tags[4] = Tag::create([
            'serial' => 'T0000005',
            'mac_addr' => 'AC:23:3F:2E:A7:B7'           
        ]);
        // $this->command->info('Tag 5 created!');

        /* Attach projects to this group, [many-to-many relationship]*/
        $group->projects()->attach($project->id);
        // $this->command->info('Project saved into Group!');

        
        foreach ($users as $user){
            /* Add user to this group */
            $group->users()->save($user);
            // $this->command->info('User saved into Group!');

            /* Assign default role to this user */
            $user->assignRole('Default');
            // $this->command->info('User assigned Role!');
        }

        for($i = 0; $i < 5; $i++){
            /* Assign tag to user */
            $users[$i]->tag()->save($tags[$i]);
            // $this->command->info('Tag '.($i + 1).' saved into '.($i + 1).'!');
        }
    }
}
