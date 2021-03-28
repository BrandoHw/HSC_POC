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
        $userCount = array();
        $userRunningCount =  array();
        //The threshold at which multiple markers conver into one large clickable marker
        $threshold = 5;
        //Convert from object to array, (array) typecasting unsuitable, it returns associative array, 
        //need numerical array for array_merge

        $beacons = json_decode(json_encode(Tag::with(['staff', 'gateway', 'gateway.location'])->has('staff')->get()));
        $beacons_r = json_decode(json_encode(Tag::with(['resident', 'gateway', 'gateway.location'])->has('resident')->get()));
        $beacons = array_merge((array) $beacons, (array) $beacons_r);

        foreach ($beacons as $user){
            if (array_key_exists($user->gateway->mac_addr, $userCount)){
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
        $userLastSeen = UserLastSeen::where('id', '==' , $id)->get();
        return $userLastSeen;
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
        $beacon = Tag::with(['resident', 'staff', 'gateway', 'gateway.location'])->where('beacon_id', $id)->get();
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
        $beacons = array_merge((array) $beacons, (array) $beacons_r);

        
        foreach ($beacons as $user){
            $user->updated_at = Carbon::parse($user->updated_at, )->tz('Asia/Kuala_Lumpur')->format('d-m-Y H:i:s');
        }
        return $beacons;
    }

}

