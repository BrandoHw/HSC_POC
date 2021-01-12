<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\User;
use App\Reader;
use App\Tag;

class InsertInitialData extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /* Create permissions */
        $permissions = [
            // User
            'user-list',
            'user-create',
            'user-edit',
            'user-delete',
            // Role
            'role-list',
            'role-create',
            'role-edit',
            'role-delete',
            // Group
            'group-list',
            'group-create',
            'group-edit',
            'group-delete',
            // Project
            'project-list',
            'project-create',
            'project-edit',
            'project-delete',
            // Company
            'company-list',
            'company-create',
            'company-edit',
            'company-delete',
            // Reader
            'reader-list',
            // 'reader-create',
            // 'reader-edit',
            // 'reader-delete',
            // Tag
            'tag-list',
            // 'tag-create',
            // 'tag-edit',
            // 'tag-delete',
            // Attendance
            // 'attendance-list',
            // 'attendance-delete',
            //Map
            'map-view',
            'map-edit'
        ];

        foreach ($permissions as $permission){
            Permission::firstOrCreate(['name' => $permission]);
        }

        /* Admin */
        $admin = Role::create([
            'name' => 'Admin',
            'color' => '#09D58F']);
        $permission = Permission::pluck('id', 'id')->all();
        $admin->syncPermissions($permission);

        /* Admin User */
        $user = User::create([
            'name' => env('ADMIN_NAME'),
            'username' => env('ADMIN_USERNAME'),
            'email' => env('ADMIN_EMAIL'),
            'password' => bcrypt(env('ADMIN_PASSWORD'))
        ]);
        $user->assignRole('Admin');

        /* Reader creation */
        
        /* For demo purpose*/
        // AC233FC0806A
        // AC233FC08070
        // AC233FC08080
        // AC233FC08064
        // AC233FC08069
        // AC233FC0806D
        // AC233FC08067
        // AC233FC08028

        $readers[0] = Reader::create([
            'serial' => 'R0000001',
            'mac_addr' => 'AC:23:3F:C0:80:6A'                   
        ]);

        $readers[1] = Reader::create([
            'serial' => 'R0000002',
            'mac_addr' => 'AC:23:3F:C0:80:70'                    
        ]);
        
        $readers[2] = Reader::create([
            'serial' => 'R0000003',
            'mac_addr' => 'AC:23:3F:C0:80:80'                    
        ]);

        $readers[3] = Reader::create([
            'serial' => 'R0000004',
            'mac_addr' => 'AC:23:3F:C0:80:64'                    
        ]);

        $readers[4] = Reader::create([
            'serial' => 'R0000005',
            'mac_addr' => 'AC:23:3F:C0:80:69'                    
        ]);

        $readers[5] = Reader::create([
            'serial' => 'R0000006',
            'mac_addr' => 'AC:23:3F:C0:80:6D'                    
        ]);

        $readers[6] = Reader::create([
            'serial' => 'R0000007',
            'mac_addr' => 'AC:23:3F:C0:80:67'                    
        ]);

        $readers[7] = Reader::create([
            'serial' => 'R0000008',
            'mac_addr' => 'AC:23:3F:C0:80:28'                    
        ]);

        /* Tag Creation */

        /* For demo purpose*/
        // AC233FA25668
        // AC233FA2566A
        // AC233FA25669
        // AC233FA25667
        // AC233FA25666

        $tags[0] = Tag::create([
            'serial' => 'T0000001',
            'mac_addr' => 'AC:23:3F:A2:56:68',           
        ]);

        $tags[1] = Tag::create([
            'serial' => 'T0000002',
            'mac_addr' => 'AC:23:3F:A2:56:6A'            
        ]);

        $tags[2] = Tag::create([
            'serial' => 'T0000003',
            'mac_addr' => 'AC:23:3F:A2:56:69'            
        ]);

        $tags[3] = Tag::create([
            'serial' => 'T0000004',
            'mac_addr' => 'AC:23:3F:A2:56:67'           
        ]);

        $tags[4] = Tag::create([
            'serial' => 'T0000005',
            'mac_addr' => 'AC:23:3F:A2:56:66'           
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
