<?php

namespace App\Http\Controllers;

use App\Alert;
use App\Attendance;
use App\Policy;
use App\Resident;
use App\Tag;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    function __construct()
    {
        $this->middleware('permission:attendance-list|attendance-delete', ['only' => ['index','show']]);
        $this->middleware('permission:attendance-delete', ['only' => ['destroy']]);
    }
    
    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function index()
    {
        $attendance_policies = Policy::where('rules_type_id', '1')->orderBy('description', 'asc')
            ->with(['scope', 'scope.tags', 'scope.tags.user', 'scope.tags.resident', 'alerts'])
            ->get();
        $attendance_alerts= Alert::orderBy('occured_at', 'asc')->whereIn('rules_id', $attendance_policies)
            ->with(['reader', 'policy', 'policy.policyType', 'tag', 'tag.resident', 'tag.user', 'user'])
            ->get();
        
        $now = Carbon::now()->toDateTimeString();
        foreach($attendance_policies as $policy){
            $policy['start_time'] = Carbon::parse($policy->datetime_at_utc);
            $policy['absent'] = -1;
            if($now > $policy['start_time']){
                if($policy->attendance != 0){
                    $policy['absent'] = count($policy->all_targets) - ($policy->alerts->where('occured_at', '>=', date($policy->datetime_at_utc))->where('occured_at', '<', date('Y-m-d H:i:s', strtotime($policy->datetime_at_utc . ' +1 day')))->unique('beacon_id')->count());
                    foreach($policy->scope->tags as $target){
                        $target['found'] = $attendance_alerts->where('rules_id', $policy->rules_id)
                        ->where('beacon_id', $target->beacon_id)
                        ->where('occured_at', '>=', date($policy->datetime_at_utc))
                        ->where('occured_at', '<', date('Y-m-d H:i:s', strtotime($policy->datetime_at_utc . ' +1 day')))
                        ->first() ?? null;
                    }
                } else {
                    $policy['absent'] = $policy->alerts->where('occured_at', '>=', date($policy->datetime_at_utc))->where('occured_at', '<', date('Y-m-d H:i:s', strtotime($policy->datetime_at_utc . ' +1 day')))->unique('beacon_id')->count();
                    foreach($policy->scope->tags as $target){
                        $target['found'] = $attendance_alerts->where('rules_id', $policy->rules_id)
                        ->where('beacon_id', $target->beacon_id)
                        ->where('occured_at', '>=', date($policy->datetime_at_utc))
                        ->where('occured_at', '<', date('Y-m-d H:i:s', strtotime($policy->datetime_at_utc . ' +1 day')))
                        ->last() ?? null;
                    }
                }
            }
        }
        
        $minDate = $attendance_alerts->first()->occured_at_tz ?? Carbon::now('Asia/Kuala_Lumpur')->setTime(0,0,0)->setTimeZone('UTC');
        $maxDate = Carbon::now('Asia/Kuala_Lumpur')->format('Y-m-d');
        return view('attendances.index',compact('attendance_alerts', 'attendance_policies', 'minDate', 'maxDate', 'now'));
    }
    
    /**
    * Display the specified resource.
    *
    * @param  \App\Attendance  $attendance
    * @return \Illuminate\Http\Response
    */
    public function show(Attendance $attendance)
    {
        //
    }

    /**
     * Display the specified resources from storage based on date.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function show_date(Request $request)
    {
        $policy = Policy::find($request['rule_id']);
        $date = $request['date'];
        $date_carbon = Carbon::parse($request['date'], 'Asia/Kuala_Lumpur');
        $now = Carbon::now();
        $today = Carbon::now('Asia/Kuala_Lumpur')->setTime(0,0,0)->setTimeZone('UTC');

        $start_time = Carbon::parse($policy->datetime_at_utc);
        $policy['absent'] = -1;

        if($date_carbon >= $today){
            if($now > $start_time){
                if($policy->attendance != 0){
                    $policy['absent'] = count($policy->all_targets) - ($policy->alerts->where('occured_at', '>=', date($policy->datetime_at_utc))->where('occured_at', '<', date('Y-m-d H:i:s', strtotime($policy->datetime_at_utc . ' +1 day')))->unique('beacon_id')->count());
                    foreach($policy->scope->tags as $target){
                        $target['found'] = $policy->alerts
                        ->where('beacon_id', $target->beacon_id)
                        ->where('occured_at', '>=', date($policy->datetime_at_utc))
                        ->where('occured_at', '<', date('Y-m-d H:i:s', strtotime($policy->datetime_at_utc . ' +1 day')))
                        ->first() ?? null;
                    }
                } else {
                    $policy['absent'] = $policy->alerts->where('occured_at', '>=', date($policy->datetime_at_utc))->where('occured_at', '<', date('Y-m-d H:i:s', strtotime($policy->datetime_at_utc . ' +1 day')))->unique('beacon_id')->count();
                    foreach($policy->scope->tags as $target){
                        $target['found'] = $policy->alerts
                        ->where('beacon_id', $target->beacon_id)
                        ->where('occured_at', '>=', date($policy->datetime_at_utc))
                        ->where('occured_at', '<', date('Y-m-d H:i:s', strtotime($policy->datetime_at_utc . ' +1 day')))
                        ->last() ?? null;
                    }
                }
            }
        } else {
            $today_check = false;
            if($policy->attendance != 0){
                $policy['absent'] = count($policy->all_targets) - ($policy->alerts->where('occured_at', '>=', date($policy->datetime_at_utc))->where('occured_at', '<', date('Y-m-d H:i:s', strtotime($policy->datetime_at_utc . ' +1 day')))->unique('beacon_id')->count());
                foreach($policy->scope->tags as $target){
                    $target['found'] = $policy->alerts
                    ->where('beacon_id', $target->beacon_id)
                    ->where('occured_at', '>=', date($policy->datetime_at_utc))
                    ->where('occured_at', '<', date('Y-m-d H:i:s', strtotime($policy->datetime_at_utc . ' +1 day')))
                    ->first() ?? null;
                }
            } else {
                $policy['absent'] = $policy->alerts->where('occured_at', '>=', date($policy->datetime_at_utc))->where('occured_at', '<', date('Y-m-d H:i:s', strtotime($policy->datetime_at_utc . ' +1 day')))->unique('beacon_id')->count();
                foreach($policy->scope->tags as $target){
                    $target['found'] = $policy->alerts
                    ->where('beacon_id', $target->beacon_id)
                    ->where('occured_at', '>=', date($policy->datetime_at_utc))
                    ->where('occured_at', '<', date('Y-m-d H:i:s', strtotime($policy->datetime_at_utc . ' +1 day')))
                    ->last() ?? null;
                }
            }

        }

        $data_update = collect();

        foreach($policy->scope->tags as $target){
            if(!empty($target->user)){
                $full_name = $target->user->full_name ?? '-';
                $type = "Staff";
            } else {
                $full_name = $target->resident->full_name ?? '-';
                $type = "Resident";
            }

            if($date_carbon >= $today){
                if($now < $start_time){
                    $attendance_badge = '<span class="badge badge-pill badge-secondary">N/A</span>';
                } else {
                    if($policy->attendance == 0){
                        $color = (isset($target->found)) ? 'danger':'success';
                        $attend = (isset($target->found)) ? 'Absent':'Present';
                    } else {
                        $color = (isset($target->found)) ? 'success':'danger';
                        $attend = (isset($target->found)) ? 'Present':'Absent';
                    }
                    $attendance_badge = '<span class="badge badge-pill badge-'.$color.'">'.$attend.'</span>';
                }
            } else {
                if($policy->attendance == 0){
                    $color = (isset($target->found)) ? 'danger':'success';
                    $attend = (isset($target->found)) ? 'Absent':'Present';
                } else {
                    $color = (isset($target->found)) ? 'success':'danger';
                    $attend = (isset($target->found)) ? 'Present':'Absent';
                }
                $attendance_badge = '<span class="badge badge-pill badge-'.$color.'">'.$attend.'</span>';
            }

            $data = collect([
                'id' => $target->beacon_id,
                'name' => $full_name,
                'type' => $type,
                'attendance' => $attendance_badge,
                'curr_loc' => $target->current_location ?? '-',
                'detected_at' => $target->found->occured_at_tz ?? '-',
            ]);

            $data_update->push($data);
        }

        return response()->json([
            "success" => "Attendance updated successfully.",
            "data" => $data_update
        ], 200);
    }
    
    /**
     * Display the specified resources from storage based on date.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function show_badge(Request $request){
        $attendance_policies = Policy::where('rules_type_id', '1')->orderBy('description', 'asc')
            ->with(['scope', 'scope.tags', 'scope.tags.user', 'scope.tags.resident', 'alerts'])
            ->get();

        $date = $request['date'];
        $date_carbon = Carbon::parse($request['date'], 'Asia/Kuala_Lumpur');
        $now = Carbon::now();
        $today = Carbon::now()->setTime(16,0,0);
        if($today > $now){
            $today = Carbon::now()->subDays(1)->setTime(16,0,0); // Set to Asia/Kuala_Lumpur 12:00;
        }

        $badge_data = collect();
        foreach($attendance_policies as $policy){
            $start_time = Carbon::parse($policy->datetime_at_utc);
            $badge_data[$policy->rules_id] = -1;

            if($date_carbon >= $today){
                if($now > $start_time){
                    if($policy->attendance != 0){
                        $badge_data[$policy->rules_id] = count($policy->all_targets) - ($policy->alerts->where('occured_at', '>=', date($policy->datetime_at_utc))->where('occured_at', '<', date('Y-m-d H:i:s', strtotime($policy->datetime_at_utc . ' +1 day')))->unique('beacon_id')->count());
                    } else {
                        $badge_data[$policy->rules_id] = $policy->alerts->where('occured_at', '>=', date($policy->datetime_at_utc))->where('occured_at', '<', date('Y-m-d H:i:s', strtotime($policy->datetime_at_utc . ' +1 day')))->unique('beacon_id')->count();
                    }
                }
            } else {
                if($policy->attendance != 0){
                    $badge_data[$policy->rules_id] = count($policy->all_targets) - ($policy->alerts->where('occured_at', '>=', date($policy->datetime_at_utc))->where('occured_at', '<', date('Y-m-d H:i:s', strtotime($policy->datetime_at_utc . ' +1 day')))->unique('beacon_id')->count());
                } else {
                    $badge_data[$policy->rules_id] = $policy->alerts->where('occured_at', '>=', date($policy->datetime_at_utc))->where('occured_at', '<', date('Y-m-d H:i:s', strtotime($policy->datetime_at_utc . ' +1 day')))->unique('beacon_id')->count();
                }
    
            }
        }

        return response()->json([
            "success" => "Badge updated successfully.",
            "badge_data" => $badge_data
        ], 200);

    }
   
    /**
    * Remove the specified resource from storage.
    *
    * @param  \App\Attendance  $attendance
    * @return \Illuminate\Http\Response
    */
    public function destroy(Attendance $attendance)
    {
        $attendance->delete();
        return redirect()->route('attendances.index')
          ->with('success','Attendance deleted successfully');
    }
}