<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Group;
use App\User;
use App\Reader;
use Carbon\Carbon;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class AttendanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {   
        /* Group User */
        $group = Group::create([
            'name' => 'Bangsar Group',
            'detail' => 'Service in the Bangsar Area',
            'schedule_id' => 2
        ]);

        /* Users Table */
        factory(User::class, 5)->create()->each(function ($user){
            $user->assignRole('Default');
            return [
                'group_id' => 1,
            ];
        });

        $tags = [
            ['serial' => 'A1000001', 'uuid' => '16aafe00e8d88a52b9f700da5e35f1ab', 'mac_add' => 'aa:23:3f:2e:a7:b7', 'user_id' =>14, 'group_id' =>1,'created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['serial' => 'A1000002', 'uuid' => 'e2c56db5dffb48d2b060d0f5a71096e0', 'mac_add' => 'aa:23:3f:2e:a7:b4', 'user_id' =>15, 'group_id' =>1,'created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['serial' => 'A1000003', 'uuid' => '0303aafe1016aafe10e8006d696e6577', 'mac_add' => 'aa:23:3f:2e:a7:b9', 'user_id' =>16, 'group_id' =>1,'created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['serial' => 'A1000004', 'uuid' => 'c56db5dffb48d2b060d0f5a71096e000', 'mac_add' => 'aa:23:3f:2e:a7:b1', 'user_id' =>17, 'group_id' =>1,'created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => Carbon::now()->format('Y-m-d H:i:s')],
            ['serial' => 'A1000005', 'uuid' => '03aafe1116aafe20000c301500040d7d', 'mac_add' => 'aa:23:3f:2e:a7:b0', 'user_id' =>18, 'group_id' =>1,'created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => Carbon::now()->format('Y-m-d H:i:s')],
        ];
        
        DB::table('tags')->insert($tags);

        $reader = Reader::create([
            'uuid' => '12345',
            'mac_add' => 'reader1',
            'location' => 'Floor 19',
        ]);

        $reader = Reader::create([
            'uuid' => '123456',
            'mac_add' => 'reader2',
            'location' => 'Floor 15',
        ]);
    }
}
