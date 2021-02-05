<?php

namespace App\Http\Controllers;

use App\Building;
use App\GatewayZone;
use App\Reader;
use App\Floor;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $id = 1;
        $readers = Reader::where('assigned', '!=', 1)
        ->join('floors', 'readers.floor_id', '=', 'floors.id')
        ->where('floors.building_id', $id)
        ->get();


        $gatewayZones = GatewayZone::join('readers', 'gateway_zones.mac_addr', '=', 'readers.mac_addr')
        ->join('floors', 'readers.floor_id', '=', 'floors.id')
        ->where('floors.building_id', $id)
        ->select('gateway_zones.*', 'readers.mac_addr', 'readers.floor_id',
                 'readers.serial', 'readers.assigned', 'floors.number', 'floors.building_id', 'floors.alias')
        ->get();
        foreach ($gatewayZones as $gatewayZone){
            $gatewayZone->geoJson = json_decode($gatewayZone->geoJson);
        }

        $building = Building::where('id', $id)->get();
     
        $floors = Floor::where('building_id', $id)->with('map')->get();
        return view('map.show', compact('readers', 'gatewayZones', 'building', 'floors'));
    }
}
