<?php

namespace Database\Seeders;

use App\User as AppUser;
use Illuminate\Database\Seeder;
use Illuminate\Foundation\Auth\User;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
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
            // // Group
            // 'group-list',
            // 'group-create',
            // 'group-edit',
            // 'group-delete',
            // // Project
            // 'project-list',
            // 'project-create',
            // 'project-edit',
            // 'project-delete',
            // // Company
            // 'company-list',
            // 'company-create',
            // 'company-edit',
            // 'company-delete',
            // Reader
            'reader-list',
            'reader-create',
            'reader-edit',
            'reader-delete',
            // Tag
            'tag-list',
            'tag-create',
            'tag-edit',
            'tag-delete',
            // Attendance
            // 'attendance-list',
            // 'attendance-delete',
            //Map
            'map-list',
            'map-view',
            'map-edit',
            //Policy
            'policy-create',
            'policy-list',
            'policy-view',
            'policy-edit',
            //Floor
            'floor-create',
            'floor-list',
            'floor-view',
            'floor-edit',
            'floor-delete'      
        ];

        foreach ($permissions as $permission){
            Permission::firstOrCreate(['name' => $permission]);
        }
        $permission = Permission::pluck('id', 'id')->all();
        $role= Role::where('id', 1)->get();
        $this->command->info($role);
        $role[0]->syncPermissions($permission);
        // $users = AppUser::permission('company-list')->get();
   
    }
}
