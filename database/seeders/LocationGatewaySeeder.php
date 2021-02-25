<?php

namespace Database\Seeders;

use App\Location;
use App\LocationType;
use App\Reader;
use Illuminate\Database\Seeder;

class LocationGatewaySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $location_type = LocationType::create([
            'location_type' => 'Room'            
        ]);
        $location_type = LocationType::create([
            'location_type' => 'Toilet'            
        ]);

        $location = Location::create([
            'location_type_id' => 1,
            'location_description' => 'Formal Living',
            'floor' => 1
        ]);
        $location = Location::create([
            'location_type_id' => 2,
            'location_description' => 'Toilet',
            'floor' => 1
        ]);
        $location = Location::create([
            'location_type_id' => 1,
            'location_description' => 'Location 2',
            'floor' => 2
        ]);
        $location = Location::create([
            'location_type_id' => 1,
            'location_description' => 'Location 3',
            'floor' => 3
        ]);
        $location = Location::create([
            'location_type_id' => 1,
            'location_description' => 'Location 4',
            'floor' => 4
        ]);
        $location = Location::create([
            'location_type_id' => 1,
            'location_description' => 'Location 5',
            'floor' => 5
        ]);
        $location = Location::create([
            'location_type_id' => 1,
            'location_description' => 'Location 6',
            'floor' => 6
        ]);
        $location = Location::create([
            'location_type_id' => 1,
            'location_description' => 'Location 7',
            'floor' => 6
        ]);

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

    }
}
