<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\User;
use App\Tag;
use App\UserType;
use App\UserRight;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class CreatePermissionTablesAWS extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $tableNames = config('permission.table_names');
        $columnNames = config('permission.column_names');

        if (empty($tableNames)) {
            throw new \Exception('Error: config/permission.php not loaded. Run [php artisan config:clear] and try again.');
        }

        Schema::create($tableNames['permissions'], function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('guard_name');
            $table->timestamps();
        });

        Schema::create($tableNames['roles'], function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->unique();
            $table->string('color')->unique();
            $table->string('guard_name');
            $table->timestamps();
        });

        Schema::create($tableNames['model_has_permissions'], function (Blueprint $table) use ($tableNames, $columnNames) {
            $table->unsignedBigInteger('permission_id');

            $table->string('model_type');
            $table->unsignedBigInteger($columnNames['model_morph_key']);
            $table->index([$columnNames['model_morph_key'], 'model_type'], 'model_has_permissions_model_id_model_type_index');

            $table->foreign('permission_id')
                ->references('id')
                ->on($tableNames['permissions'])
                ->onDelete('cascade');

            $table->primary(['permission_id', $columnNames['model_morph_key'], 'model_type'],
                    'model_has_permissions_permission_model_type_primary');
        });

        Schema::create($tableNames['model_has_roles'], function (Blueprint $table) use ($tableNames, $columnNames) {
            $table->unsignedBigInteger('role_id');

            $table->string('model_type');
            $table->unsignedBigInteger($columnNames['model_morph_key']);
            $table->index([$columnNames['model_morph_key'], 'model_type'], 'model_has_roles_model_id_model_type_index');

            $table->foreign('role_id')
                ->references('id')
                ->on($tableNames['roles'])
                ->onDelete('cascade');

            $table->primary(['role_id', $columnNames['model_morph_key'], 'model_type'],
                    'model_has_roles_role_model_type_primary');
        });

        Schema::create($tableNames['role_has_permissions'], function (Blueprint $table) use ($tableNames) {
            $table->unsignedBigInteger('permission_id');
            $table->unsignedBigInteger('role_id');

            $table->foreign('permission_id')
                ->references('id')
                ->on($tableNames['permissions'])
                ->onDelete('cascade');

            $table->foreign('role_id')
                ->references('id')
                ->on($tableNames['roles'])
                ->onDelete('cascade');

            $table->primary(['permission_id', 'role_id'], 'role_has_permissions_permission_id_role_id_primary');
        });

        app('cache')
            ->store(config('permission.cache.store') != 'default' ? config('permission.cache.store') : null)
            ->forget(config('permission.cache.key'));
        
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
            'attendance-list',
            'attendance-delete',
            //Map
            'map-list',
            'map-create',
            'map-edit',
            'map-delete',
            // Policy
            'policy-list',
            'policy-create',
            'policy-edit',
            'policy-delete',
            // Alert
            'alert-list',
            'alert-create',
            'alert-edit',
            'alert-delete',
            // Tracking
            'tracking-list',
            'tracking-create',
            'tracking-edit',
            'tracking-delete',
            // Report
            'report-list',
            'report-create',
            'report-edit',
            'report-delete',
            // Setting
            'setting-list',
            'setting-create',
            'setting-edit',
            'setting-delete'
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
            'fName' => 'Super',
            'lName' => 'Admin',
            'phone_number' => '601112341234',
            'username' => 'admin',
            'password' => bcrypt(env('ADMIN_PASSWORD'))
        ]);
        $tag = Tag::find(211);
        $tag->user()->save($user);

        $userType = UserType::find(1);
        $userType->user()->save($user);

        $userRight = UserRight::find(1);
        $userRight->user()->save($user);

        $user->assignRole('Admin');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $tableNames = config('permission.table_names');

        if (empty($tableNames)) {
            throw new \Exception('Error: config/permission.php not found and defaults could not be merged. Please publish the package configuration before proceeding, or drop the tables manually.');
        }

        Schema::drop($tableNames['role_has_permissions']);
        Schema::drop($tableNames['model_has_roles']);
        Schema::drop($tableNames['model_has_permissions']);
        Schema::drop($tableNames['roles']);
        Schema::drop($tableNames['permissions']);
    }
}
