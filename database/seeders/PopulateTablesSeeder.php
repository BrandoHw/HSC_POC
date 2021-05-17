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
use Faker\Generator as Faker;

class PopulateTablesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /* Projects Table */
        // factory(Project::class, 50)->create();
        
        /* Users Table */
        // factory(User::class, 50)->create()->each(function ($user){
        //     $user->assignRole('Admin');
        // });

        /* Readers Table */
        factory(Reader::class, 150)->create();

        /* Tags Table */
        factory(Tag::class, 50)->create();
        
        /* Companies Table */
        factory(Company::class, 5)->create()->each(function ($company){
            // Create 3 buildings for this company with floor number between 1 to 5
            factory(Building::class, 3)->create(['company_id' => $company->id])->each(function ($building){

                // Create floor object for this building according to the floor number
                foreach(range(1, $building->floor_num) as $floorNum){
                    $floor = Floor::create([
                        'number' => $floorNum, 
                        'building_id' => $building->id
                    ]);
                    
                    // Assign 2 readers that dont belong to any floor/building to this floor
                    $readersNull = Reader::doesntHave('floor')->get()->random(2);

                    foreach($readersNull as $reader){
                        $floor->readers()->save($reader);
                    }
                }
            });
        });

        /* Groups Table */
        factory(Group::class, 5)->create()->each(function ($group){
            // Assign 2 random project to this group
            $projects = Project::get()->random(2);
            
            foreach($projects as $project){
                $project->groups()->attach($group->id);
            }

            // Assign 6 random users that dont belong to any group to this group
            $users = User::doesntHave('group')->get()->random(6);
            
            foreach($users as $user){
                $group->users()->save($user);
                
                // Assign a tag to this user
                $tag = Tag::doesntHave('user')->first();
                $user->tag()->save($tag);
            }

            // Create schedule object that belong to this group
            $schedule = Schedule::create(['group_id' => $group->id]);

            // Create timeblocks from monday to friday
            for($i = 1; $i <= 5; $i++){
                // Morning shift
                $timeblocks[0] = Timeblocks::create([
                    'start_time' => Carbon::createFromTime(9, 0, 0, 'Asia/Kuala_Lumpur')->format('H:i:s'),
                    'end_time' => Carbon::createFromTime(12, 0, 0, 'Asia/Kuala_Lumpur')->format('H:i:s'),
                    'day' => $i,
                    'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                    'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                ]);

                // Afternoon shift
                $timeblocks[1] = Timeblocks::create([
                    'start_time' => Carbon::createFromTime(13, 0, 0, 'Asia/Kuala_Lumpur')->format('H:i:s'),
                    'end_time' => Carbon::createFromTime(17, 0, 0, 'Asia/Kuala_Lumpur')->format('H:i:s'),
                    'day' => $i,
                    'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                    'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                ]);
                
                foreach($timeblocks as $timeblock){
                    // Save this timeblock to the schedule of this group
                    $schedule->timeblocks()->save($timeblock);

                    // Assign a random company to this timeblock
                    $company = Company::get()->random(1)[0];
                    $company->timeblocks()->save($timeblock);
                    
                    // Assign a random building of the company to this timeblock
                    $building = $company->buildings->random(1)[0];
                    $building->timeblocks()->save($timeblock);
                }
            }
        });
    }
}
