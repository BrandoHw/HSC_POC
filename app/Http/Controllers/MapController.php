<?php

namespace App\Http\Controllers;

use App\Building;
use App\Floor;
use App\GatewayZone;
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
        $this->middleware('permission:map-list|map-create|map-edit|map-delete', ['only' => ['index','show', 'edit']]);
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
        
        $readers = Reader::where('assigned', '!=' , 1)->get();
        $gatewayZones = GatewayZone::all();

        foreach ($gatewayZones as $gatewayZone){
            $gatewayZone->geoJson = json_decode($gatewayZone->geoJson);
        }
        return view('map.show', compact('readers', 'gatewayZones'));
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
        //
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

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        //$readers = Reader::where('assigned', '!=' , 1)->get();

        //Floors - All Floors for building of $id
        //Readers_Floors - All reader for all floors
        //Reader - All unassigned readers (i.e readers without a drawn zone)
    
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

    function console_log( $data ){
        echo '<script>';
        echo 'console.log('. json_encode( $data ) .')';
        echo '</script>';
      }
}
