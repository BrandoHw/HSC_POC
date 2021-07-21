<?php

namespace App\Http\Controllers;

use App\Tag;
use App\UserLastSeen;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class UserLastSeenController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $userCount = array(); // A count of the number of user in each gateway zone (Key = Mac, Value = Number of Users)
        $userRunningCount =  array(); // (Key = Mac, Value = Number of Users if above threshold, else 0 (e.g [0 0, 7]) For 3 locations with 1 4 and 7 users in zone)
        //The threshold at which multiple markers converge into one large clickable marker
        $threshold = 5;
        //Convert from object to array, (array) typecasting unsuitable, it returns associative array, 
        //need numerical array for array_merge

        $beacons = json_decode(json_encode(Tag::with(['staff', 'gateway', 'gateway.location'])->has('staff')->has('gateway')->get()));
        $beacons_r = json_decode(json_encode(Tag::with(['resident', 'gateway', 'gateway.location'])->has('resident')->has('gateway')->get()));
        
        usort($beacons_r, function ($a, $b) {
            return strcmp($a->resident->resident_fName, $b->resident->resident_fName);
        });

        $beacons = array_merge((array) $beacons, (array) $beacons_r);


        foreach ($beacons as $user){
            $user->grey_marker = Carbon::parse($user->updated_at)->tz('Asia/Kuala_Lumpur')->lt(Carbon::now()->subMinutes(5));
            $user->updated_at = Carbon::parse($user->updated_at)->tz('Asia/Kuala_Lumpur')->format('d-m-Y H:i:s');
            $user->draw = Carbon::parse($user->updated_at)->tz('Asia/Kuala_Lumpur')->gt(Carbon::now()->subMinutes(60));
            if (array_key_exists($user->gateway->mac_addr, $userCount)){
                if ($user->draw)
                    $userCount[$user->gateway->mac_addr] = $userCount[$user->gateway->mac_addr] + 1;
            }else{
                $userCount[$user->gateway->mac_addr] = 1;
                $userRunningCount[$user->gateway->mac_addr] = 0;
            };
    
        }
        foreach ($userCount as  $key => $value){
            if ($value >= $threshold){
                $userRunningCount[$key] = $value;
            }
        }
        return compact('userCount', 'userRunningCount', 'beacons');
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
     * Get specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function get(Request $request)
    {
        //
        $id = $request->input('id');
        $beacon = json_decode(json_encode(Tag::with(['resident', 'staff', 'gateway', 'gateway.location'])->where('beacon_id', $id)->get()));
        $beacon[0]->grey_marker = Carbon::parse($beacon[0]->updated_at)->tz('Asia/Kuala_Lumpur')->lt(Carbon::now()->subMinutes(5));
        $beacon[0]->updated_at = Carbon::parse($beacon[0]->updated_at)->tz('Asia/Kuala_Lumpur')->format('d-m-Y H:i:s');
    
        // if (empty($beacon[0]->staff) && !empty($beacon[0]->staff_klia)){
        //     $beacon[0]->staff = (object)array();
        //     $beacon[0]->staff->fName =  $beacon[0]->staff_klia->name;
        //     $beacon[0]->staff->lName =  "";
        // }
        
        return $beacon;
    }

    public function group(Request $request)
    {
        //
        $mac_addr = $request->input('mac_addr');

        //Get all Tag where gateway's mac is equal to the request mac_addr and which have an associated resident/staff
        $beacons = json_decode(json_encode(
            Tag::whereHas('gateway', function($q) use($mac_addr){
                $q->where('mac_addr', $mac_addr);})
            ->whereHas('resident')
            ->with(['resident', 'gateway', 'gateway.location'])
            ->get()
        ));

        $beacons_r = json_decode(json_encode(
            Tag::whereHas('gateway', function($q) use($mac_addr){
                $q->where('mac_addr', $mac_addr);})
            ->whereHas('staff')
            ->with(['staff', 'gateway', 'gateway.location'])
            ->get()
        ));

        // $beacons_s = json_decode(json_encode(
        //     Tag::whereHas('gateway', function($q) use($mac_addr){
        //         $q->where('mac_addr', $mac_addr);})
        //     ->whereHas('staff_klia')
        //     ->with(['staff_klia', 'gateway', 'gateway.location'])
        //     ->get()
        // ));

        $beacons = array_merge((array) $beacons, (array) $beacons_r);//, (array) $beacons_s);

        
        foreach ($beacons as $user){
            $user->updated_at = Carbon::parse($user->updated_at)->tz('Asia/Kuala_Lumpur')->format('d-m-Y H:i:s');
            // if (!property_exists($user, 'staff') && property_exists($user, 'staff_klia')){
            //     $user->staff = (object)array();
            //     $user->staff->fName =  $user->staff_klia->name;
            //     $user->staff->lName =  "";
            // }
        }

     
        return $beacons;
    }

    public function getUserPositions()
    {
        $userCount = array();
        $userRunningCount =  array();
        //The threshold at which multiple markers converge into one large clickable marker
        $threshold = 5;
        //Convert from object to array, (array) typecasting unsuitable, it returns associative array, 
        //need numerical array for array_merge
        $beacons = json_decode(json_encode(Tag::with(['staff', 'gateway', 'gateway.location'])->has('staff')->has('gateway')->get()));
        $beacons_r = json_decode(json_encode(Tag::with(['resident', 'gateway', 'gateway.location'])->has('resident')->has('gateway')->get()));
       
        // $beacons_s = json_decode(json_encode(Tag::with(['staff_klia', 'gateway', 'gateway.location'])->has('staff_klia')->has('gateway')->get()));
        
        // foreach ($beacons_s as $user){
        //     if (!property_exists($user, 'staff') && property_exists($user, 'staff_klia')){
        //         $user->staff = (object)array();
        //         $user->staff->fName =  $user->staff_klia->name;
        //         $user->staff->lName =  "";
        //     }
        // }
        
        usort($beacons_r, function ($a, $b) {
            return strcmp($a->resident->resident_fName, $b->resident->resident_fName);
        });

        $beacons = array_merge((array) $beacons, (array) $beacons_r);//, (array) $beacons_s);

        foreach ($beacons as $user){
            $user->grey_marker = Carbon::parse($user->updated_at)->tz('Asia/Kuala_Lumpur')->lt(Carbon::now()->subMinutes(5));
            $user->updated_at = Carbon::parse($user->updated_at)->tz('Asia/Kuala_Lumpur')->format('d-m-Y H:i:s');
            $user->draw = Carbon::parse($user->updated_at)->tz('Asia/Kuala_Lumpur')->gt(Carbon::now()->subMinutes(60));
            if (array_key_exists($user->gateway->mac_addr, $userCount)){
                if ($user->draw)
                    $userCount[$user->gateway->mac_addr] = $userCount[$user->gateway->mac_addr] + 1;
            }else{
                $userCount[$user->gateway->mac_addr] = 1;
                $userRunningCount[$user->gateway->mac_addr] = 0;
            };
    
        }
        foreach ($userCount as  $key => $value){
            if ($value >= $threshold){
                $userRunningCount[$key] = $value;
            }
        }
        return compact('userCount', 'userRunningCount', 'beacons');
    }


}

