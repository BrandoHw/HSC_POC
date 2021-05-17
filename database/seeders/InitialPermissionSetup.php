<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\User;
use App\Tag;
use App\UserType;
use App\UserRight;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class InitialPermissionSetup extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $permissions = [
            'alert-list', 'alert-delete',
            'attendance-list', 'attendance-delete',
            'beacon-list', 'beacon-create','beacon-edit','beacon-delete',
            'floor-list', 'floor-create', 'floor-edit', 'floor-delete',
            'gateway-list', 'gateway-create','gateway-edit','gateway-delete',
            'location-list', 'location-create','location-edit','location-delete',
            'policy-list', 'policy-create','policy-edit','policy-delete',
            'report-list', 'report-create','report-edit','report-delete',
            'resident-list', 'resident-create','resident-edit','resident-delete',
            'role-list', 'role-create', 'role-edit', 'role-delete',
            'tracking-list',
            'user-list', 'user-create', 'user-edit', 'user-delete',
        ];

        foreach ($permissions as $permission){
            Permission::firstOrCreate(['name' => $permission]);
        }

        // /* Admin */
        $admin = Role::create([
            'name' => 'Admin',
            'color' => '#09D58A']);
        // $admin = Role::find(2);
        $permission_admin = Permission::pluck('id', 'id')->all();
        $admin->syncPermissions($permission_admin);

        $permissions_name_default = [
            'alert-list',
            'attendance-list', 
            'beacon-list', 
            'floor-list',
            'gateway-list', 
            'location-list', 
            'policy-list',
            'report-list',
            'resident-list', 
            'role-list', 
            'tracking-list',
            'user-list', 
        ];

        /* Non-Admin */
        $default = Role::create([
            'name' => 'Default',
            'color' => '#874EFE']);
        // $default = Role::find(1);
        $permission_default = Permission::whereIn('name', $permissions_name_default)->pluck('id', 'id')->all();
        $default->syncPermissions($permission_default);

        /* Admin User */
        // $user = User::create([

        // ]);
        // $tag = Tag::find(201);
        // $tag->user()->save($user);

        // $userType = UserType::find(1);
        // $userType->user()->save($user);

        // $userRight = UserRight::find(1);
        // $userRight->user()->save($user);

        // $user->assignRole('Admin');

        /* Users Table */
        factory(User::class, 100)->create()->each(function ($user){
            $user->assignRole('Admin');
        });
        factory(User::class, 400)->create()->each(function ($user){
            $user->assignRole('Default');
        });
    }
}
