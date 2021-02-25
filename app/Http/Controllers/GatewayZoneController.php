<?php

namespace App\Http\Controllers;

use App\GatewayZone;
use App\Reader;
use Illuminate\Http\Request;

class GatewayZoneController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        
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
        $input = [];
        $input['geoJson'] = json_encode($request->input('geoJson'));
        $input['mac_addr'] = $request->input('mac_addr');
        $input['location'] =  $request->input('location');

        $gatewayZone = GatewayZone::create($input);
        $id = $gatewayZone->id;
  
        // $gatewayZoneEager = GatewayZone::join('readers', 'gateway_zones.mac_addr', '=', 'readers.mac_addr')
        // ->join('floors', 'readers.floor_id', '=', 'floors.id')
        // ->where('gateway_zones.id', $id)
        // ->select('gateway_zones.*', 'readers.mac_addr', 'readers.floor_id',
        //          'readers.serial', 'readers.assigned', 'floors.number', 'floors.building_id', 'floors.alias')
        // ->first();


        //
        $gateway = Reader::where('mac_addr', $input['mac_addr'])
                         ->update(['location_id' => $input['location']]);


        // $gatewayZoneEager = GatewayZone::join('gateways_table2', 'gateway_zones2.mac_addr', '=', 'gateways_table2.mac_addr')
        // ->join('locations_master_table2', 'gateways_table2.location_id', '=', 'locations_master_table2.location_master_id')
        // ->join('floors', 'locations_master_table2.floor', '=', 'floors.id')
        // ->where('gateway_zones2.id', $id)
        // ->select('gateway_zones2.*', 'gateways_table2.mac_addr', 'locations_master_table2.floor',
        // 'gateways_table2.serial', 'gateways_table2.assigned', 'floors.number', 'floors.building_id', 'floors.alias')
        // ->first();

        
        $gatewayZoneEager = GatewayZone::with('gateway', 'gateway.location', 'gateway.location.floor_level')
        ->where('id', '=', $id)
        ->first();

        $gatewayZoneEager->geoJson = json_decode($gatewayZoneEager->geoJson);
        return $gatewayZoneEager;
       
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
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        //
        $gatewayZones = $request->input('gateways');

        foreach ($gatewayZones as $gatewayZone){
            $id = $gatewayZone->id;
            $geoJson = json_encode(($gatewayZone->geoJson));
            $macAddr = $gatewayZone->mac_addr;
            $location = $gatewayZone->location;
            GatewayZone::where('id', $id)->update(['geoJson' => $geoJson, 'mac_addr' => $macAddr, 'location' => $location]);
        }
        return "Success";
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

        return GatewayZone::destroy($id);
        
    }

    /**
     * Display a listing of the resource without returning a view.
     *
     * @return \Illuminate\Http\Response
     */
    public function get()
    {
        //
        $gatewayZones = GatewayZone::all();
        return $gatewayZones;
    }

    
    /**
     * Remove the specified resource from storage, using the post method for AJAX
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroyAjax(Request $request)
    {
        //
        $id = $request->input('id');
        return GatewayZone::destroy($id);
    }

    /**
     * Update the specified resource in storage, using post for AJAX
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateAjax(Request $request)
    {
        //
        $this->console_log($request);
        $gatewayZones = $request->input('gateways');
        $this->console_log($gatewayZones[0]);
        foreach ($gatewayZones as $gatewayZone){
            $id = $gatewayZone['id'];
            $geoJson = json_encode(($gatewayZone['geoJson']));
            $macAddr = $gatewayZone['mac_addr'];
            $location = $gatewayZone['location'];
            GatewayZone::where('id', $id)->update(['geoJson' => $geoJson, 'mac_addr' => $macAddr, 'location' => $location]);
        }
        return "Success";
    }

    function console_log($output, $with_script_tags = true) {
        $js_code = 'console.log(' . json_encode($output, JSON_HEX_TAG) . 
    ');';
        if ($with_script_tags) {
            $js_code = '<script>' . $js_code . '</script>';
        }
        echo $js_code;
    }

}
