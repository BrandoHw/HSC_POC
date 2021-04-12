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
        $permissions_default = [
            'user-list',
            'reader-list',
            'tag-list',
            'attendance-list',
            'map-list',
            'floor-list',
            'policy-list',
            'alert-list',
            'tracking-list',
            'report-list',
            'setting-list'
        ];

        $permissions_admin = [
            'user-create', 'user-edit', 'user-delete',
            'role-create', 'role-edit', 'role-delete',
            'reader-create','reader-edit','reader-delete',
            'tag-create','tag-edit','tag-delete',
            'attendance-delete',
            'floor-create', 'floor-edit', 'floor-delete',
            'map-create','map-edit','map-delete',
            'policy-create','policy-edit','policy-delete',
            'alert-create','alert-edit','alert-delete',
            'tracking-create','tracking-edit','tracking-delete',
            'report-create','report-edit','report-delete',
            'setting-create','setting-edit','setting-delete'
        ];

        foreach ($permissions_default as $permission){
            Permission::firstOrCreate(['name' => $permission]);
        }

        /* Non-Admin */
        $default = Role::create([
            'name' => 'Default',
            'color' => '#09D58F']);
        $permission_default = Permission::pluck('id', 'id')->all();
        $default->syncPermissions($permission_default);

        foreach ($permissions_admin as $permission){
            Permission::firstOrCreate(['name' => $permission]);
        }

        /* Admin */
        $admin = Role::create([
            'name' => 'Admin',
            'color' => '#09D58A']);
        $permission_admin = Permission::pluck('id', 'id')->all();
        $admin->syncPermissions($permission_admin);

        /* Admin User */
        $user = User::find(1);
        $tag = Tag::find(201);
        $tag->user()->save($user);

        $userType = UserType::find(1);
        $userType->user()->save($user);

        $userRight = UserRight::find(1);
        $userRight->user()->save($user);

        $user->assignRole('Admin');
    }
}
