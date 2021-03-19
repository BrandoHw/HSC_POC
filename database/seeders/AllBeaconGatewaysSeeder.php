<?php

namespace Database\Seeders;

use App\GatewayZone;
use App\Location;
use App\Reader;
use App\Tag;
use Illuminate\Database\Seeder;

class AllBeaconGatewaysSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        // for ($i = 2; $i<8; $i++){
        //     for ($j = 1; $j < 70; $j++){
        //         if ($i == 7){
        //             $floor = 14;
        //         }else{
        //             $floor = $i;
        //         }
        //         $location_description = "Room"."-"."0".$floor."-".$j;
        //         Location::create([
        //             'location_type_id' => 1,
        //             'floor' => $floor,
        //             'location_description' => $location_description,
        //         ]);
        //     }
        // }

    
        // $hex = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9', 'A', 'B', 'C', 'D', 'E', 'F'];
        // $base = ['C', 'D'];
        // $base_mac = 'AC:23:3F:C0:9';
        // $counter = 0;
        // for ($h=0; $h<2; $h++){
        //     for ($i=0; $i<16; $i++){
        //         for ($j=0; $j<16; $j++){
        //             Reader::create([
        //                 'mac_addr' => $base_mac.$base[$h].":".$hex[$i].$hex[$j],
        //                 'location_id' => 899 + $counter,
        //                 'serial' => null,
        //             ]);
        //             $counter++;

        //             if ($counter>413)
        //                 break;
        //         }
        //         if ($counter>413)
        //             break;
        //     }
        //     if ($counter>413)
        //         break;
        // }
    //     $x = 420;
    //     $y = 200;
    //     $gateways = Reader::whereBetween('gateway_id', [527, 940])->get();
    //     $counter = 0;
    //     for ($h=0; $h<6; $h++){
    //         for ($i=0; $i<10; $i++){
    //             for ($j=0; $j<7; $j++){
    //                 $geoJson = (object)[];
    //                 $geoJson->type = "Polygon";
    //                 $geoJson->coordinates = [[
    //                     [$j*$x, $i*$y],
    //                     [($j+1)*$x, $i*$y],
    //                     [($j+1)*$x, ($i+1)*$y],
    //                     [$j*$x, ($i+1)*$y],
    //                     [$j*$x, $i*$y],
    //                 ]];
    //                 $geoJson->marker = (array("lat"=> ($i+0.5)*$y, "lng"=> ($j+0.5)*$x));
    //                 $geoJson = json_encode($geoJson);
            
    //                 GatewayZone::create([
    //                         'geoJson' => $geoJson,
    //                         'mac_addr' => $gateways[$counter]->mac_addr,
    //                         'location' => 6,
    //                     ],
    //                 );
    //                 $counter++;
    //                 if ($counter>413)
    //                     break;
    //             }
    //             if ($counter>413)
    //                 break;
    //         }
    //         if ($counter>413)
    //             break;
    //     }
    // }

    $hex = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9', 'A', 'B', 'C', 'D', 'E', 'F'];
    $base = "AC:23:3F:A9:30:";
  
        for ($i=0; $i<16; $i++){
            for ($j=0; $j<16; $j++){
                Tag::create([
                    'beacon_type' => 1,
                    'beacon_mac' => $base.$hex[$i].$hex[$j],
                    'current_loc' => rand(527, 940),
                ]);
            }
        
        }
    }

     


}
