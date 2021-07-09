<?php

namespace App\Http\Controllers;

use App\Alert;
use App\Attendance_KLIA;
use App\Policy;
use App\Reader;
use App\Resident;
use App\Tag;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\GatewayZone;
use App\Building;
use App\Floor;

class HomeKliaController extends Controller
{
    //
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
        $alerts = Alert::where('resolved_at', '=', null) ->has('tag.resident')->orderBy('alert_id', 'asc')->get();
      
        $alerts_count = $alerts->count();
        $alerts_last = ($alerts->first()->alert_id ?? 0) - 1;
       
        $readers_count = Reader::count();
        $tags_count = Tag::count();
        $residents_count = Resident::count();

        return view('klia.dashboard.home', compact('gatewayZones', 'building', 'floors', 
            'alerts', 'alerts_count', 'alerts_last', 'tags_count', 'residents_count', 'readers_count', 
        ));
    }

    // Return the number of Active and Inactive tags to display on a pie chart
    // An Active tag is a tag that has been visible to a gateway within the past day
    public function activeTags()
    {
        $today = Carbon::now('Asia/Kuala_Lumpur')->setTime(0,0,0)->setTimeZone('UTC');
        $tags = Tag::where('updated_at', '>=', $today);
        $active_tags_count = $tags->count();
        $tags_count = Tag::count() - $active_tags_count;

        return compact('active_tags_count', 'tags_count');
    }

    // Return the attendance from the past 7 days, split the data up by day, count the number of unique tags that appear in each day
    // The Date Column is the date that this attendance was recorded in UTC+8 
    // (If it was UTC-0 then users in who attend in a single Malaysian day will appear could appear twice in UTC-0)
    public function attendanceWeek(){
        $start_date = Carbon::now('Asia/Kuala_Lumpur')->setTime(0,0,0)->setTimeZone('UTC')->subDays(5)->toDateString();

        $dates = array();
        $labels = array();
        for ($i = 0; $i < 7; $i++){
            $date = Carbon::now('Asia/Kuala_Lumpur')->setTime(0,0,0)->subDays($i)->toDateString();
            array_push($labels, substr($date, 5));
            array_push($dates, $date);
        }
        $dates = array_reverse($dates);
        $labels = array_reverse($labels);
        $attendance = Attendance_KLIA::where('date' ,'>=', $start_date)
                                    ->whereNotNull('staff_name')
                                    ->whereNotNull('location_name')
                                    ->get();
        $arr = array();

        foreach ($attendance as $key => $item) {
           $arr[$item['date']][$key] = $item;
        }
        
        ksort($arr, SORT_NUMERIC);
        
     
        $tmp = array();
        $count = array();
        $countSeries = array();
        foreach ($arr as $date => $date_arr){
            $i = 0;
            foreach($date_arr as $k => $v)
                $tmp[$date][$k] = $v->tag_mac;  
            $tmp[$date] = array_unique($tmp[$date]);
            $count[$date] = count($tmp[$date]);
            //$countSeries[$i] = array("x" => $date, "y" => count($tmp[$date]));
        }

        $series = array();
        foreach ($dates as $index => $date){
            if (array_key_exists($date, $count)){
                array_push($series, $count[$date]);
            }else{
                array_push($series, 0);
            }
        }
        return response()->json([
            'count' => $count,
            'series' => $series,
            'tags' => $tmp,
            'dates' => $dates,
            'labels' => $labels,
            'start_date' => $start_date
        ], 200);
    }

    //Return a List of assigned gateways, and list of beacons that have been active within the past 5 minutes
    //Mark the gateways that have a staff member within it's vicinity
    public function locationPresence(){
        $gateways = Reader::where('assigned', 1)->with('location')->get();
        $beacons = Tag::where('updated_at', '>=', Carbon::now()->subMinutes(5))
                    ->where('current_loc', '!=', null)
                    ->with('gateway')
                    ->get();
        $gateways_array = array();

        foreach ($gateways as $gateway){
            $gateways_array[$gateway->mac_addr] = $gateway;
            $gateways_array[$gateway->mac_addr]->icon = false;
            $gateways_array[$gateway->mac_addr]->location_name = $gateway->location->location_description;
        }

        foreach ($beacons as $beacon){
            $key = $beacon->gateway->mac_addr;
            if (array_key_exists($key, $gateways_array)){
                $gateways_array[$key]->icon = true;
            }
        }
        return response()->json([
            'gateways' => $gateways,
            'beacons' => $beacons,
            'gateways_array' => $gateways_array,
        ], 200);

    }

}
