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
use Illuminate\Support\Arr;
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
                ->has('gateway')
                ->has('gateway.location')
                ->has('gateway.location.floor_level')
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

        $alerts = Alert::where('occured_at', '>=', $today)
            ->orderBy('occured_at', 'desc')
            ->with(['reader', 'reader.location', 'policy', 'policy.policyType', 'tag', 'tag.resident', 'tag.user', 'user'])
            ->get();
      
        $alerts_count = $alerts->count();
        $alerts_last = Alert::orderBy('alert_id', 'desc')->first()->alert_id ?? 0;

        $policies_count = Policy::count();
        $readers_count = Reader::count();
        $tags_count = Tag::count();
        $residents_count = Resident::count();

        $attendance_policies = Policy::where('rules_type_id', '1')
            ->orderBy('description', 'asc')
            ->with(['scope', 'scope.tags'])
            ->get();
        
        $attendance_alerts= Alert::orderBy('occured_at', 'asc')
            ->whereIn('rules_id', $attendance_policies)
            ->with(['reader', 'reader.location', 'policy', 'policy.policyType', 'tag', 'tag.resident', 'tag.user', 'user'])
            ->get();

        /* Radial Chart Series */
        $attendance = array();
        $now = Carbon::now()->toDateTimeString();
        foreach($attendance_policies as $policy){
            $start_time = Carbon::parse($policy->datetime_at_utc);
            $percentage = 0;
            if($now >= $start_time){
                if($policy->attendance == 0){
                    $absent = $policy->alerts
                        ->where('occured_at', '>=', date($policy->datetime_at_utc))
                        ->where('occured_at', '<', date('Y-m-d H:i:s', strtotime($policy->datetime_at_utc . ' +1 day')))
                        ->unique('beacon_id')
                        ->count();
                } else {
                    $absent = count($policy->all_targets) 
                        - ($policy->alerts
                            ->where('occured_at', '>=', date($policy->datetime_at_utc))
                            ->where('occured_at', '<', date('Y-m-d H:i:s', strtotime($policy->datetime_at_utc . ' +1 day')))
                            ->unique('beacon_id')
                            ->count());
                }
                $percentage = (1 - $absent/count($policy->all_targets)) * 100;
            }
            array_push($attendance, $percentage);
        }

        /* Radial chart color */
        $colors_default = ["#827af3", "#6ce6f4", "#a09e9e", "#fbc647"];
        $num =  count($attendance_policies)/4;
        $whole = floor($num);
        $fraction = $num - $whole; 

        $colors = [];
        for($i = 0; $i <= $whole; $i++){
            if($i == $whole ){
                for($j = 0; $j < $fraction * 4; $j++){
                    array_push($colors, $colors_default[$j]);
                };
            } else {
                array_push($colors, $colors_default[0], $colors_default[1], $colors_default[2], $colors_default[3]);
            }
        }

        return view('home', compact('gatewayZones', 'building', 'floors', 
            'alerts', 'alerts_count', 'alerts_last', 'policies_count', 'tags_count', 'residents_count', 'readers_count', 
            'attendance_policies', 'attendance_alerts', 'attendance', 'colors', 'whole', 'fraction'
        ));
    }
}
