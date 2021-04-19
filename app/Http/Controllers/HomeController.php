<?php

namespace App\Http\Controllers;

use App\Alert;
use App\Building;
use App\Floor;
use App\GatewayZone;
use App\Policy;
use App\Reader;
use App\Resident;
use App\Tag;
use Carbon\Carbon;
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

        $building = Building::find($id)->get();
     
        $floors = Floor::where('building_id', $id)->with('map')->orderBy('number', 'asc')->get();

        $today = Carbon::now('Asia/Kuala_Lumpur')->setTime(0,0,0)->setTimeZone('UTC');
        $alerts = Alert::where('occured_at', '>=', $today)->orderBy('occured_at', 'desc')->get();
        $policies = Policy::get();
        $readers = Reader::get();
        $tags = Tag::get();
        $residents = Resident::get();
        $attendance_policies = Policy::where('rules_type_id', '1')->orderBy('description', 'asc')->get();
        $attendance_alerts= Alert::orderBy('occured_at', 'asc')->whereIn('rules_id', $attendance_policies)->get();
        return view('home', compact('gatewayZones', 'building', 'floors', 
            'alerts', 'policies', 'tags', 'residents', 'readers', 'attendance_policies', 'attendance_alerts'));
    }
}
