<?php

namespace App\Http\Controllers;

use App\Alert;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;


class AlertController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:alert-list|alert-archive', ['only' => ['index','show', 'updates']]);
        $this->middleware('permission:alert-archive', ['only' => ['destroys']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $alerts = Alert::orderBy('alert_id', 'asc')->get();
        return view('alerts.index', compact('alerts'));
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
         //Get all Tag where gateway's mac is equal to the request mac_addr and which have an associated resident/staff


         if ($id === 'unresolved'){
            $alert_s = 
                Alert::whereHas('tag', function($q){
                    $q->whereHas('staff');})
                    ->with(['tag', 'tag.staff', 'reader', 'reader.location',  'policy'])
                    ->where('action', '==', 0)
                    ->orderBy('occured_at', 'desc')
                    ->get();

            foreach($alert_s as $alert){
                $alert->full_name = $alert->tag->staff->fName." ".$alert->tag->staff->lName;
                $alert->duration = Carbon::parse($alert->occured_at)->diffForHumans();//gmdate('H:i:s', Carbon::now()->diffInSeconds(Carbon::parse($alert->occured_at)));
            }

            $alert_r =
                Alert::whereHas('tag', function($q){
                    $q->whereHas('resident');})
                    ->with(['tag', 'tag.resident', 'reader', 'reader.location',  'policy'])
                    ->get();

            foreach($alert_r as $alert){
                $alert->full_name = $alert->tag->resident->resident_fName." ".$alert->tag->resident->resident_lName;
                $alert->duration = Carbon::parse($alert->occured_at)->diffForHumans();
            }

            $alert_s = json_decode(json_encode($alert_s));
            $alert_r = json_decode(json_encode($alert_r));
            $alerts = array_merge((array) $alert_s, (array) $alert_r);

            foreach($alert_r as $alert){
                $alert->full_name = $alert->tag->resident->resident_fName." ".$alert->tag->resident->resident_lName;
                $alert->duration = Carbon::parse($alert->occured_at)->diffForHumans();
            }
            usort($alerts, function($a, $b) {
                return strtotime($b->occured_at) - strtotime($a->occured_at);
            });

        }else{
            $alerts = Alert::where('alert_id', '==', $id)->get();
        }

    

        return $alerts;//[$alerts, $alert_c];
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
     * Update the specified resources in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updates(Request $request)
    {
        $ids = $request->alerts_id;

        $alerts = Alert::find($ids)->whereNull('resolved_at');
        $user = User::find($request['user_id']);

        $resolved_at = Carbon::now();

        foreach($alerts as $alert){
            $alert->user()->associate($user)->save();
            $alert->resolved_at = $resolved_at;
            $alert->save();
        }
        
        $message = "Alert resolved successfully.";
        
        if(count($ids) > 1){
            $message = "Alerts resolved successfully.";
        }
        return response()->json([
            "success" => $message,
            "user" => $user->full_name,
            "alerts" => $alerts,
            "resolved_at" => $alerts->first()->resolved_at_tz
        ], 200);
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
     * Remove the specified resources from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function destroys(Request $request)
    {
        $ids = $request->alerts_id;

        Alert::destroy($ids);

        if(count($ids) > 1){
            return response()->json([
                "success" => "Alerts archived successfully."
            ], 200);
        } else {
            return response()->json([
                "success" => "Alert archived successfully."
            ], 200);
        }
    }
}
