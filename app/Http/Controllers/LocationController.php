<?php

namespace App\Http\Controllers;

use App\Location;
use App\Floor;
use App\GatewayZone;
use App\LocationType;
use App\Reader;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class LocationController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:location-list|location-create|location-edit|location-delete', ['only' => ['index','show', 'edit']]);
        $this->middleware('permission:location-create', ['only' => ['create','store']]);
        $this->middleware('permission:location-edit', ['only' => ['update']]);
        $this->middleware('permission:location-delete', ['only' => ['destroy', 'delete']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $locations = Location::with(['type', 'floor_level'])->has('floor_level')->get()->sortBy('floor');
        $floors = Floor::orderBy('number','asc')->get();
        $types = LocationType::all();
        return view('locations.index',compact('locations', 'floors', 'types'));
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
        $validator = Validator::make($request->all(), [
            'location_description' => 'required',
            'location_type_id' => 'required',
            'floor' => 'required',
        ]);
        
        if($validator->fails()){
            return response()->json([
                "errors" => $validator->errors()]);
        }

        $location = Location::create($request->all());
        $location_w = Location::with(['type', 'floor_level'])->where('location_master_id', $location->location_master_id)->first();
        if ($location->location_master_id){
            return response()->json([
                'success'=>'Location added succesfully.',
                "location" => $location_w], 
            200);
        } 
        else{
            return response()->json([
                'failure'=>'Failed to create location',
                'location' => $location_w],
            200);
        }
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
        $location = Location::where('location_master_id', $id)->get()[0];
        $floors = Floor::orderBy('number','asc')->pluck('alias', 'floor_id')->all();
        $types = LocationType::pluck('location_type', 'type_id')->all();
        return view('locations.edit',compact('location', 'floors', 'types'));
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
        $location = Location::where('location_master_id', $id)->first();
        // request()->validate([
        //     'location_description' => 'required|unique:location,location_description,'.$location->location_master_id,
        //     'location_type' => 'required|unique:readers,location_type,'.$location->location_master_id,
        //     'floor' => 'required|unique:readers,mac_addr,'.$location->location_master_id,
        // ]);

        $validator = Validator::make($request->all(), [
            'location_description' => 'required',
            'location_type' => 'required',
            'floor' => 'required',
        ]);
        
        if($validator->fails()){
            return redirect()->route('locations.index')
            ->with('errors', $validator->errors());
        }

        $location->update(['location_description' => $request->get('location_description'),
                            'location_type_id' => $request->get('location_type'),
                            'floor' => $request->get('floor')]);
            
        return redirect()->route('locations.index')
            ->with('success',$request->all());
    }

    /**
     * Remove the specified resources from storage.
     *
     *@param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        //
        $ids = $request['ids'];
     
        return response()->json([
            'success'=>'Location added succesfully.',
        ],
        200);

    }
    /**
     * Remove the specified resources from storage.
     *
     *@param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request)
    {
        //
        $ids = $request['ids'];
        // $this->console_log($ids);

        //Find Gateways where location = ids
        $gateways = Reader::whereIn('location_id', $ids)->get();
        $gatewayMacs = $gateways->pluck('mac_addr');
        $gatewayZones = GatewayZone::whereIn('mac_addr', $gatewayMacs)->get();
        foreach($gatewayZones as $gatewayZone){
            $gatewayZone->delete();
        }
        foreach($gateways as $gateway){
            $gateway->update(['location_id' => null]);
        }
        $deletedRows = Location::whereIn('location_master_id', $ids)->delete();
        
        $locations = Location::with(['type', 'floor_level'])->get()->sortBy('floor');
        //notyf method for ajax
        return response()->json([
            'success'=>'Location removed succesfully.',
            'gateways' => $gateways,
            'gatewayMac' => $gatewayMacs,
            'gatewayZones' => $gatewayZones,
            'ids' => $ids,
            'deletedRows' => $deletedRows,
            'locations' => $locations,
        ],
        200);
    }

    function console_log( $data ){
        echo '<script>';
        echo 'console.log('. json_encode( $data ) .')';
        echo '</script>';
      }
    
}
