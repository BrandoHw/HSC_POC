<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run(){
        /* Default */
        // $files_arr = scandir( dirname(__FILE__) ); //store filenames into $files_array
        // foreach ($files_arr as $key => $file){
        //     if ($file !== 'DatabaseSeeder.php' && $file[0] !== "." ){
        //         $this->call( explode('.', $file)[0] );
        //     }
        // }

        /* Define our own seeding sequence */
        // $this->call(PermissionsTableSeeder::class);
        // $this->command->info('Permissions table has been seeded!');

        // $this->call(CreateInitialRolesSeeder::class);
        // $this->command->info('Initial roles have been created!');
        
        // $this->call(CreateAdminUserSeeder::class);
        // $this->command->info('Admin user has been created!');
        
        // $this->call(PopulateTablesSeeder::class);
        // $this->command->info('Other tables has been populated!');

        // $this->call(PopulateDataForTesting::class);
        // $this->command->info('Testing data has been added!');

        // $this->call(PopulateDataForDemo::class);
        // $this->command->info('Demo data has been added!');
    }
}
