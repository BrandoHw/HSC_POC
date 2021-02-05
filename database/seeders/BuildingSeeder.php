<?php

namespace Database\Seeders;

use App\Building;
use App\Floor;
use App\MapFile;
use Illuminate\Database\Seeder;

class BuildingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $buildings = Building::firstOrCreate([
            'name' => 'HSC Resident Home',
            'detail' => 'HSC Resident Home',
            'floor_num' => 6,
            'address' => 'N\A',
            'lat' => 0,
            'lng' => 0             
        ]);

        $floor = Floor::firstOrCreate([
            'number' => 1,
            'alias' => "1st Storey",
            'building_id' => 1,
        ]);

        $floor = Floor::firstOrCreate([
            'number' => 2,
            'alias' => "2nd Storey",
            'building_id' => 1,
        ]);

        $floor = Floor::firstOrCreate([
            'number' => 3,
            'alias' => "3rd Storey",
            'building_id' => 1,
        ]);

        $floor = Floor::firstOrCreate([
            'number' => 4,
            'alias' => "4th Storey",
            'building_id' => 1,
        ]);

        $floor = Floor::firstOrCreate([
            'number' => 5,
            'alias' => "5th Storey",
            'building_id' => 1,
        ]);

        $floor = Floor::firstOrCreate([
            'number' => 6,
            'alias' => "Roof",
            'building_id' => 1,
        ]);

        $mapfile = MapFile::firstOrCreate([
            'name' => 'grey',
            'url' => '/storage/greyimage.png',
            'floor_id' => 1,
        ]);
    }
}
