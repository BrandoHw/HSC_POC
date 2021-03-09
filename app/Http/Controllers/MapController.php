<?php

namespace App\Http\Controllers;

use App\Building;
use App\Floor;
use App\GatewayZone;
use App\Location;
use App\LocationType;
use App\Reader;
use Illuminate\Http\Request;

class MapController extends Controller
{
    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    function __construct()
    {
        $this->middleware('permission:map-list|map-create|map-edit|map-delete', ['only' => ['index','show']]);
        $this->middleware('permission:map-create', ['only' => ['create','store']]);
        $this->middleware('permission:map-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:map-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $id = 1;
        $gatewayZones = GatewayZone::with(['gateway', 
        'gateway.location', 
        'gateway.location.floor_level' => function($q) use($id) {
            // Query the name field in status table
                $q->where('building_id', '=', $id);}])
        ->get();
        foreach ($gatewayZones as $gatewayZone){
            $gatewayZone->geoJson = json_decode($gatewayZone->geoJson);
            $gatewayZone->mac_addr = $gatewayZone->gateway->mac_addr;
            $gatewayZone->floor = $gatewayZone->gateway->location->floor_level->id;
            $gatewayZone->serial = $gatewayZone->gateway->serial;
            $gatewayZone->assigned = $gatewayZone->gateway->assigned;
            $gatewayZone->number = $gatewayZone->gateway->location->floor_level->number;
            $gatewayZone->building_id= $gatewayZone->gateway->location->floor_level->building_id;
            $gatewayZone->alias = $gatewayZone->gateway->location->floor_level->alias;
        }

        $building = Building::where('id', $id)->get();
     
        $floors = Floor::where('building_id', $id)->with('map')->orderBy('number', 'asc')->get();
        //TODO: check map url whether image exists then change to greyimage/noimage found
        return view('map.show', compact('gatewayZones', 'building', 'floors'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // $gatewayZones = GatewayZone::join('gateways_table2', 'gateway_zones2.mac_addr', '=', 'gateways_table2.mac_addr')
        // ->join('locations_master_table2', 'gateways_table2.location_id', '=', 'locations_master_table2.location_master_id')
        // ->join('floors', 'locations_master_table2.floor', '=', 'floors.id')
        // ->where('floors.building_id', $id)
        // ->select('gateway_zones2.*', 'gateways_table2.mac_addr', 'locations_master_table2.floor',
        //          'gateways_table2.serial', 'gateways_table2.assigned', 'floors.number', 'floors.building_id', 'floors.alias')
        // ->get();
        // foreach ($gatewayZones as $gatewayZone){
        //     $gatewayZone->geoJson = json_decode($gatewayZone->geoJson);
        // }

        $gatewayZones = GatewayZone::with(['gateway', 
        'gateway.location', 
        'gateway.location.floor_level' => function($q) use($id) {
            // Query the name field in status table
                $q->where('building_id', '=', $id);}])
        ->get();
        foreach ($gatewayZones as $gatewayZone){
            $gatewayZone->geoJson = json_decode($gatewayZone->geoJson);
            $gatewayZone->mac_addr = $gatewayZone->gateway->mac_addr;
            $gatewayZone->floor = $gatewayZone->gateway->location->floor_level->id;
            $gatewayZone->serial = $gatewayZone->gateway->serial;
            $gatewayZone->assigned = $gatewayZone->gateway->assigned;
            $gatewayZone->number = $gatewayZone->gateway->location->floor_level->number;
            $gatewayZone->building_id= $gatewayZone->gateway->location->floor_level->building_id;
            $gatewayZone->alias = $gatewayZone->gateway->location->floor_level->alias;
        }

        $building = Building::where('id', $id)->get();
     
        $floors = Floor::where('building_id', $id)->with('map')->orderBy('number', 'asc')->get();
        //TODO: check map url whether image exists then change to greyimage/noimage found
        return view('map.show', compact('gatewayZones', 'building', 'floors'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        // $readers = Reader::where('assigned', '!=', 1)
        // ->leftJoin('locations_master_table2', 'gateways_table2.location_id', '=', 'locations_master_table2.location_master_id')
        // ->leftJoin('floors', 'locations_master_table2.floor', '=', 'floors.id')
        // ->select('gateways_table2.*', 'locations_master_table2.*',
        //         'floors.number', 'floors.building_id', 'floors.alias')
        // ->get();

        
        // $gatewayZones = GatewayZone::join('gateways_table2', 'gateway_zones2.mac_addr', '=', 'gateways_table2.mac_addr')
        // ->join('locations_master_table2', 'gateways_table2.location_id', '=', 'locations_master_table2.location_master_id')
        // ->join('floors', 'locations_master_table2.floor', '=', 'floors.id')
        // ->where('floors.building_id', $id)
        // ->select('gateway_zones2.*', 'gateways_table2.mac_addr', 'locations_master_table2.floor',
        //          'gateways_table2.serial', 'gateways_table2.assigned', 'floors.number', 'floors.building_id', 'floors.alias')
        // ->get();
        // foreach ($gatewayZones as $gatewayZone){
        //     $gatewayZone->geoJson = json_decode($gatewayZone->geoJson);
        // }
        $readers = Reader::where('assigned', '!=', 1)
        ->with('location', 'location.floor_level:id,number,building_id,alias')
        ->get();

        $gatewayZones = GatewayZone::with(['gateway', 
        'gateway.location', 
        'gateway.location.floor_level' => function($q) use($id) {
            // Query the name field in status table
                $q->where('building_id', '=', $id);}])
        ->get();
        foreach ($gatewayZones as $gatewayZone){
            $gatewayZone->geoJson = json_decode($gatewayZone->geoJson);
            $gatewayZone->mac_addr = $gatewayZone->gateway->mac_addr;
            $gatewayZone->floor = $gatewayZone->gateway->location->floor_level->id;
            $gatewayZone->serial = $gatewayZone->gateway->serial;
            $gatewayZone->assigned = $gatewayZone->gateway->assigned;
            $gatewayZone->number = $gatewayZone->gateway->location->floor_level->number;
            $gatewayZone->building_id= $gatewayZone->gateway->location->floor_level->building_id;
            $gatewayZone->alias = $gatewayZone->gateway->location->floor_level->alias;
        }

        $building = Building::where('id', $id)->get();
     
        $floors = Floor::where('building_id', $id)->with('map')->orderBy('number', 'asc')->get();
        //return compact('readers', 'gatewayZones', 'gatewayZones2', 'building', 'floors');
        return view('map.edit', compact('readers', 'gatewayZones', 'building', 'floors'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

      /**
     * Retrieve floors and location type for location creation form
     */
    public function formdata()
    {
        //
        $floors = Floor::orderBy('number','asc')->get();
        $types = LocationType::all();
        return compact('floors', 'types');
    }

    public function locationdata()
    {
        //
        $data = Location::with('type')->with('floor_level')->get();
        return compact('data');
    }

    public function listdata(Request $request){
        // $list_data;
        // return $list_data;

    }
    
    public function dashboard()
    {
        //
        $id = 1;
        $gatewayZones = GatewayZone::with(['gateway', 
        'gateway.location', 
        'gateway.location.floor_level' => function($q) use($id) {
            // Query the name field in status table
                $q->where('building_id', '=', $id);}])
        ->get();
        foreach ($gatewayZones as $gatewayZone){
            $gatewayZone->geoJson = json_decode($gatewayZone->geoJson);
            $gatewayZone->mac_addr = $gatewayZone->gateway->mac_addr;
            $gatewayZone->floor = $gatewayZone->gateway->location->floor_level->id;
            $gatewayZone->serial = $gatewayZone->gateway->serial;
            $gatewayZone->assigned = $gatewayZone->gateway->assigned;
            $gatewayZone->number = $gatewayZone->gateway->location->floor_level->number;
            $gatewayZone->building_id= $gatewayZone->gateway->location->floor_level->building_id;
            $gatewayZone->alias = $gatewayZone->gateway->location->floor_level->alias;
        }

        $building = Building::where('id', $id)->get();
     
        $floors = Floor::where('building_id', $id)->with('map')->orderBy('number', 'asc')->get();
        //TODO: check map url whether image exists then change to greyimage/noimage found
        return view('map.dashboard', compact('gatewayZones', 'building', 'floors'));
    }

    function console_log( $data ){
        echo '<script>';
        echo 'console.log('. json_encode( $data ) .')';
        echo '</script>';
      }
}
