<?php

namespace App\Http\Controllers;

use App\TagDataLog;
use App\Building;
use App\Group;
use App\Services\AttendanceService;
use App\Services\AttendanceStatusService;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class TagDataLogController extends Controller
{
   /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function index()
    {
        $tag_data_logs = TagDataLog::latest('last_detected_at')->get();
        $groups = Group::pluck('name', 'id')->all();
        $groupNum = Group::count();
        return view('tag_data_logs.index', compact('tag_data_logs', 'groups', 'groupNum'));
    }
    

    public function process(Request $request, AttendanceStatusService $attendanceStatusService)
    {
        // $group_id = $request->input('group');
        // $date = $request->input('enddate');
        // $date = new Carbon($date);
        // $groups = Group::where('id', $group_id)->get();
        // $date_end = $date->copy()->endOfDay();
        // $date_start =  $date->startOfDay()->subDays(7);
        // $attendanceData = $attendanceService->generateAttendanceData($groups, $date_start, $date_end);

        $group = Group::find($request['group_id']);
        $date_start = new Carbon(explode(' - ', $request['date_range'])[0]);
        $date_end = new Carbon(explode(' - ', $request['date_range'])[1]);
        $date_end = $date_end->addDays(1)->subSecond(1);

        $tag_data_logs = collect();

        foreach($group->tags as $tag){

            $logs = $tag->tagDataLogs->filter(function($value, $key) use ($date_start, $date_end){
                if(($value->first_detected_at >= $date_start) && $value->last_detected_at <= $date_end){
                    return $value;
                }
            });

            foreach($logs as $log){
                $building = Building::find($log->building_id);

                $check_in_message = $attendanceStatusService->check_attendance_status($log->check_in_status);
                $check_out_message = $attendanceStatusService->check_attendance_status($log->check_out_status);

                $data = collect([
                    'user' => $tag->user->name,
                    'mac_addr' => $log->mac_addr,
                    'first_detected_at' => $log->first_detected_at,
                    'check_in_status' => $check_in_message,
                    'last_detected_at' => $log->last_detected_at,
                    'check_out_status' => $check_out_message,
                    'location' => "<em>".$building->company->name."</em>: ".$building->name,
                    'duration' => $log->duration,
                ]);
                $tag_data_logs->push($data);
            }
        }
        
        return response()->json([
            'data' => $tag_data_logs,
        ], 200);
    }
    

     function console_log( $data ){
        echo '<script>';
        echo 'console.log('. json_encode( $data ) .')';
        echo '</script>';
      }

}
