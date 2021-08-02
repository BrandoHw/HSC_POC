<?php

namespace App\Http\Controllers;

use App\Alert;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;



class AlertController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:alert-list|alert-delete', 
            ['only' => ['index','show', 'updates', 'new_alerts', 'new_alerts_table', 'resolve', 'resolve_all']]);
        $this->middleware('permission:alert-delete', ['only' => ['destroys']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $alerts = Alert::orderBy('alert_id', 'asc')
        ->with(['reader.location.floor_level', 'policy', 'policy.policyType', 'tag', 'tag.resident', 'tag.user', 'user'])
        ->get();
        $alerts_last = $alerts->last()->alert_id ?? 0;
        return view('alerts.index', compact('alerts', 'alerts_last'));
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

        $limit = 6;
         if ($id === 'unresolved'){
            $alert_s = 
                Alert::whereHas('tag', function($q){
                    $q->whereHas('staff');})
                    ->with(['tag', 'tag.staff', 'reader', 'reader.location',  'policy'])
                    ->orderBy('occured_at', 'desc')
                    ->limit($limit)
                    ->get();

            $alert_s_count =   
                Alert::whereHas('tag', function($q){
                    $q->whereHas('staff');})
                    ->with(['tag', 'tag.staff', 'reader', 'reader.location',  'policy'])
                    ->orderBy('occured_at', 'desc')
                    ->count();
            foreach($alert_s as $alert){
                $alert->full_name = $alert->tag->staff->fName." ".$alert->tag->staff->lName;
                $alert->duration = Carbon::parse($alert->occured_at)->diffForHumans();//gmdate('H:i:s', Carbon::now()->diffInSeconds(Carbon::parse($alert->occured_at)));
            }

            $alert_r =
                Alert::whereHas('tag', function($q){
                    $q->whereHas('resident');})
                    ->with(['tag', 'tag.resident', 'reader', 'reader.location',  'policy'])
                    ->orderBy('occured_at', 'desc')
                    ->limit($limit)
                    ->get();

            $alert_r_count = 
                Alert::whereHas('tag', function($q){
                    $q->whereHas('resident');})
                    ->with(['tag', 'tag.resident', 'reader', 'reader.location',  'policy'])
                    ->orderBy('occured_at', 'desc')
                    ->count();
            foreach($alert_r as $alert){
                $alert->full_name = $alert->tag->resident->tag->resident_fName." ".$alert->tag->resident->tag->resident_lName;
                $alert->duration = Carbon::parse($alert->occured_at)->diffForHumans();
            }

            $alert_s = json_decode(json_encode($alert_s));
            $alert_r = json_decode(json_encode($alert_r));
            $alerts = array_merge((array) $alert_s, (array) $alert_r);

            foreach($alert_r as $alert){
                $alert->full_name = $alert->tag->resident->tag->resident_fName." ".$alert->tag->resident->tag->resident_lName;
                $alert->duration = Carbon::parse($alert->occured_at)->diffForHumans();
            }
            usort($alerts, function($a, $b) {
                return strtotime($b->occured_at) - strtotime($a->occured_at);
            });

        }else{
            $alerts = Alert::where('alert_id', '==', $id)->get();
        }

    
        $counts = $alert_r_count + $alert_s_count;
        return compact('alerts', 'counts');
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
        
        if(count($alerts)<1){
            return response()->json([
                "success" => "No unresolved alert found!",
                "found" => false,
            ], 200);
        } else {
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
                "found" => true,
                "resolved_at" => $alerts->first()->resolved_at_tz
            ], 200);
        }

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

    public function getAlerts()
    {
        //
         //Get all Tag where gateway's mac is equal to the request mac_addr and which have an associated resident/staff
        $id = 'unresolved';
        $limit = 6;
         if ($id === 'unresolved'){
            $alert_s = 
                Alert::whereHas('tag', function($q){
                    $q->whereHas('staff');})
                    ->with(['tag', 'tag.staff', 'reader', 'reader.location',  'policy'])
                    ->whereNull('resolved_at')
                    ->orderBy('occured_at', 'desc')
                    ->limit($limit)
                    ->get();

            $alert_s_count =   
                Alert::whereHas('tag', function($q){
                    $q->whereHas('staff');})
                    ->with(['tag', 'tag.staff', 'reader', 'reader.location',  'policy'])
                    ->whereNull('resolved_at')
                    ->orderBy('occured_at', 'desc')
                    ->count();

            foreach($alert_s as $alert){
                $alert->full_name = $alert->tag->staff->fName." ".$alert->tag->staff->lName;
                $alert->duration = Carbon::parse($alert->occured_at)->diffForHumans();//gmdate('H:i:s', Carbon::now()->diffInSeconds(Carbon::parse($alert->occured_at)));
                $alert->image_url = null;
                if (Storage::disk('s3-resized')->exists('resized'.'-'.'staff'.'-'.$alert->tag->staff->staff_id.'.jpg')) {
                    $alert->image_url = Storage::disk('s3-resized')->url('resized'.'-'.'staff'.'-'.$alert->tag->staff->staff_id.'.jpg');
                }else if (Storage::disk('s3-resized')->exists('resized'.'-'.'staff'.'-'.$alert->tag->staff->staff_id.'.jpeg')) {
                    $alert->image_url = Storage::disk('s3-resized')->url('resized'.'-'.'staff'.'-'.$alert->tag->staff->staff_id.'.jpeg');
                }else if (Storage::disk('s3-resized')->exists('resized'.'-'.'staff'.'-'.$alert->tag->staff->staff_idd.'.png')) {
                    $alert->image_url = Storage::disk('s3-resized')->url('resized'.'-'.'staff'.'-'.$alert->tag->staff->staff_id.'.png');
                }
            }

            $alert_r =
                Alert::whereHas('tag', function($q){
                    $q->whereHas('resident');})
                    ->with(['tag', 'tag.resident', 'reader', 'reader.location',  'policy'])
                    ->whereNull('resolved_at')
                    ->orderBy('occured_at', 'desc')
                    ->limit($limit)
                    ->get();

            $alert_r_count = 
                Alert::whereHas('tag', function($q){
                    $q->whereHas('resident');})
                    ->with(['tag', 'tag.resident', 'reader', 'reader.location',  'policy'])
                    ->whereNull('resolved_at')
                    ->orderBy('occured_at', 'desc')
                    ->count();

            foreach($alert_r as $alert){
                $alert->full_name = $alert->tag->resident->resident_fName." ".$alert->tag->resident->resident_lName;
                $alert->duration = Carbon::parse($alert->occured_at)->diffForHumans();
                $alert->image_url = null;
                if (Storage::disk('s3-resized')->exists('resized-residents'.'/'.'resident'.'-'.$alert->tag->resident->resident_id.'.jpg')) {
                    $alert->image_url = Storage::disk('s3-resized')->url('resized-residents'.'/'.'resident'.'-'.$alert->tag->resident->resident_id.'.jpg');
                }else if (Storage::disk('s3-resized')->exists('resized-residents'.'/'.'resident'.'-'.$alert->tag->resident->resident_id.'.jpeg')) {
                    $alert->image_url = Storage::disk('s3-resized')->url('resized-residents'.'/'.'resident'.'-'.$alert->tag->resident->resident_id.'.jpeg');
                }else if (Storage::disk('s3-resized')->exists('resized-residents'.'/'.'resident'.'-'.$alert->tag->resident->resident_id.'.png')) {
                    $alert->image_url = Storage::disk('s3-resized')->url('resized-residents'.'/'.'resident'.'-'.$alert->tag->resident->resident_id.'.png');
                }
            }

            $alert_s = json_decode(json_encode($alert_s));
            $alert_r = json_decode(json_encode($alert_r));
            $alerts = array_merge((array) $alert_s, (array) $alert_r);

            usort($alerts, function($a, $b) {
                return strtotime($b->occured_at) - strtotime($a->occured_at);
            });

            $type = config('app.type');

        }else{
            $alerts = Alert::where('alert_id', '==', $id)->get();
        }

        $counts = $alert_r_count + $alert_s_count;
        return compact('alerts', 'counts', 'type');
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

        $message = "Alert archived successfully.";

        if(count($ids) > 1){
            $message = "Alerts archived successfully.";
        };

        return response()->json([
            "success" => $message,
        ], 200);
        
    }

    /**
     * Get new alerts for dashboard.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function new_alerts(Request $request)
    {
        $last_id = $request->last_id;

        $today = Carbon::now('Asia/Kuala_Lumpur')->setTime(0,0,0)->setTimeZone('UTC');
        $alerts = Alert::where('occured_at', '>=', $today)
            ->orderBy('occured_at', 'desc')
            ->with(['reader', 'reader.location', 'policy', 'policy.policyType', 'tag', 'tag.resident', 'tag.user', 'user'])
            ->get();

        $total_alerts_num = $alerts->count();
        $alerts_new = $alerts->where('alert_id', '>', $last_id);
        $tags = $alerts->unique('beacon_id')->pluck('tag');
        
        $alerts_grouped = collect();
        $alerts_num = 0;
        $alerts_by_tag = $alerts_new->groupBy('beacon_id');
        foreach($alerts_by_tag as $alerts_tag){
            $id = $alerts_tag->first()->beacon_id;

            $filters = collect();
            $all_counts = 0;
            foreach($alerts_tag->groupBy('rules_id') as $alerts_rule){
                $rule_id = $alerts_rule->first()->rule_id;
                $unresolved = $alerts_rule->whereNull('resolved_at');
                $resolved = $alerts_rule->whereNotNull('resolved_at');
                
                $data = null;
                if($unresolved->count() > 0){
                    $data = $unresolved->first();
                    $data['counts'] = $unresolved->count();
                } else {
                    $data = $resolved->first();
                    $data['counts'] = 0;
                }
                $data['time_diff_tz'] = $data->time_diff_tz;
                $data['occured_at_time_tz'] = $data->occured_at_time_tz;
                $all_counts += $data['counts'];

                $filters->put($rule_id, $data);
            }
            $alerts_num += $all_counts;
            $alerts_grouped->put($id, [$filters, $alerts_tag->first()->tag->current_location, $all_counts]);
        }

        foreach($tags as $tag){
            if(empty($alerts_grouped[$tag->beacon_id])){
                $alerts_grouped->put($tag->beacon_id, [[], $tag->current_location, -1]);
            }
        }

        if(count($alerts_new) > 0){
            $last_id = $alerts_new->sortBy('alert_id')->last()->alert_id;
        }

        return response()->json([
            "success" => $alerts_num.' New Alerts!',
            "alerts_grouped" => $alerts_grouped,
            "new_alerts_num" => $alerts_num,
            "total_alerts_num" => $total_alerts_num,
            "last_id" => $last_id,
            "today" => $today,
        ], 200);
        
    }

    /**
     * Get new alerts for index datatable.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function new_alerts_table(Request $request)
    {
        $last_id = $request->last_id;

        $alerts_new = Alert::where('alert_id', '>', $last_id)->get();

        $alerts_num = 0;
        if(count($alerts_new) > 0){
            $alerts_num = count($alerts_new);
            $last_id = $alerts_new->sortBy('alert_id')->last()->alert_id;
        }

        if($alerts_num < 1){
            return response()->json([
                "success" => 'No new alert found!',
                "alerts_num" => $alerts_num,
                "last_id" => $last_id,
            ], 200);
        } else {
            $data_update = collect();

            foreach($alerts_new as $alert){
                if(!empty($alert->tag->user)){
                    $full_name = $alert->tag->user->full_name ?? '-';
                } else {
                    $full_name = $alert->tag->resident->full_name ?? '-';
                }
    
                if($alert->policy->trashed()){
                    $policy = '<span class="text-secondary"><em>' . $alert->policy->description . '<span class="small text-secondary">[deleted]</span></em></span>';
                } else {
                    $policy = $alert->policy->description;
                }
    
                $color = $alert->resolved_at ? 'success':'danger';
                $status = $alert->resolved_at ? 'Resolved':'Unresolved';
                $status_full = '<span class="badge badge-pill iq-bg-' . $color .'">' . $status . '</span>';
    
                $data = collect([
                    'id' => $alert->alert_id,
                    'policy_type' => $alert->policy->policyType->rules_type_desc,
                    'policy' => $policy,
                    'subject' => $full_name,
                    'location' => $alert->reader->location_full ?? "-",
                    'occured_at' => $alert->occured_at_tz,
                    'status' => $status_full,
                    'resolved_by' => $alert->user->full_name ?? "-",
                    'resolved_at' => $alert->resolved_at_tz
                ]);
    
                $data_update->push($data);
            }

            if($alerts_num > 1){
                $message = $alerts_num . " new alerts found!";
            } else {
                $message = $alerts_num . " new alert found!";
            }

            return response()->json([
                "success" => "Alerts updated successfully. ". $message,
                "data" => $data_update,
                "alerts_num" => $alerts_num,
                "last_id" => $last_id,
            ], 200);
        }

        
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
        // $alerts = Alert::where('occured_at', '>=', $today)
        $alerts = Alert::where('alert_id', '>=', '3382')
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
