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
        $alerts = Alert::orderBy('alert_id', 'asc')->with(['reader', 'policy', 'policy.policyType', 'tag', 'tag.resident', 'tag.user', 'user'])->get();
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

    /**
     * Remove the specified resources from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function new_alerts(Request $request)
    {
        $last_id = $request->last_id;

        $today = Carbon::now('Asia/Kuala_Lumpur')->setTime(0,0,0)->setTimeZone('UTC');
        $alerts = Alert::where('occured_at', '>=', $today)
            ->whereNull('resolved_at')
            ->orderBy('occured_at', 'desc')
            ->with(['reader', 'reader.location', 'policy', 'policy.policyType', 'tag', 'tag.resident', 'tag.user', 'user'])
            ->get();

        $alerts_new = $alerts->where('alert_id', '>', $last_id);
        $tags = $alerts->unique('beacon_id')->pluck('tag');
        
        $alerts_grouped = collect();
        $alerts_by_tag = $alerts_new->groupBy('beacon_id');
        foreach($alerts_by_tag as $alerts){
            $id = $alerts->first()->beacon_id;
            $counts = $alerts->countBy('rules_id');
            
            $filters = $alerts->unique('rules_id');
            foreach($filters as $filter){
                $filter['time_diff_tz'] = $filter->time_diff_tz;
                $filter['occured_at_time_tz'] = $filter->occured_at_time_tz;
                $filter['counts'] = $counts[$filter->rules_id];
            }

            $all_counts = 0;
            foreach($counts as $count){
                $all_counts += $count;
            }

            $alerts_grouped->put($id, [$filters, $alerts->first()->tag->current_location, $all_counts]);
        }

        foreach($tags as $tag){
            if(empty($alerts_grouped[$tag->beacon_id])){
                $alerts_grouped->put($tag->beacon_id, [[], $tag->current_location, -1]);
            }
        }

        $alerts_num = 0;
        if(count($alerts_new) > 0){
            $alerts_num = count($alerts_new);
            $last_id = $alerts_new->sortBy('alert_id')->last()->alert_id;
        }

        return response()->json([
            "success" => $alerts_num.' alerts returned',
            "alerts_grouped" => $alerts_grouped,
            "alerts_num" => $alerts_num,
            "last_id" => $last_id
        ], 200);
        
    }

    /**
     * Resolve the specified alerts according to tag in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function resolve_all(Request $request)
    {
        $tag_id = $request['tag_id'];

        $today = Carbon::now('Asia/Kuala_Lumpur')->setTime(0,0,0)->setTimeZone('UTC');
        $alerts = Alert::where('occured_at', '>=', $today)
            ->where('beacon_id', $tag_id)
            ->whereNull('resolved_at')
            ->with(['reader', 'reader.location', 'policy', 'policy.policyType', 'tag', 'tag.resident', 'tag.user', 'user'])
            ->get();

        $user = User::find($request['user_id']);

        $resolved_at = Carbon::now();

        foreach($alerts as $alert){
            $alert->user()->associate($user)->save();
            $alert->resolved_at = $resolved_at;
            $alert->save();
        }
        $message = "Alert resolved successfully.";
        
        if(count($alerts) > 1){
            $message = "Alerts resolved successfully.";
        }
        return response()->json([
            "success" => $message,
            "tag_id" => $tag_id,
        ], 200);
    }

    /**
     * Resolve the specified alerts according to tag and policy in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function resolve(Request $request)
    {
        $tag_id = $request['tag_id'];
        $policy_id = $request['rule_id'];

        $today = Carbon::now('Asia/Kuala_Lumpur')->setTime(0,0,0)->setTimeZone('UTC');
        $alerts = Alert::where('occured_at', '>=', $today)
            ->where('beacon_id', $tag_id)
            ->where('rules_id', $policy_id)
            ->whereNull('resolved_at')
            ->with(['reader', 'reader.location', 'policy', 'policy.policyType', 'tag', 'tag.resident', 'tag.user', 'user'])
            ->get();

        $user = User::find($request['user_id']);

        $resolved_at = Carbon::now();

        foreach($alerts as $alert){
            $alert->user()->associate($user)->save();
            $alert->resolved_at = $resolved_at;
            $alert->save();
        }
        $message = "Alert resolved successfully.";
        
        if(count($alerts) > 1){
            $message = "Alerts resolved successfully.";
        }
        return response()->json([
            "success" => $message,
            "tag_id" => $tag_id,
        ], 200);
    }
}
