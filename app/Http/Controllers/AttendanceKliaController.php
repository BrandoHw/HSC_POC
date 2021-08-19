<?php

namespace App\Http\Controllers;

use App\Attendance_KLIA;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Alert;
class AttendanceKliaController extends Controller
{
    public function index (){
        $attendance = Attendance_KLIA::latest('last_seen')->get();
        return view('klia.reports.index', compact('attendance'));
    }

    public function getAttendanceKLIA(Request $request)
    {
        // $request['date_range'] = "05/05/2021 - 06/06/2021";
        $date_start = new Carbon(explode(' - ', $request['date_range'])[0]);
        $date_end = new Carbon(explode(' - ', $request['date_range'])[1]);
        $date_end = $date_end->addDays(1)->subSecond(1);
     
        $attendance = json_decode(json_encode(Attendance_KLIA::whereBetween('date', [$date_start, $date_end])
        ->whereNotNull('staff_name')
        ->whereNotNull('location_name')
        ->orderBy('first_seen', 'desc')
        ->get()));

        foreach ($attendance as $att){
            //$att->full_name = $att->tag->resident->resident_fName." ". $att->tag->resident->resident_lName;
            $att->time_missing = Carbon::parse($att->last_seen)->diffForHumans(); 
            $att->first_seen = Carbon::createFromFormat('Y-m-d H:i:s', $att->first_seen, 'UTC')
            ->setTimezone('Asia/Kuala_Lumpur')
            ->format('Y-m-d H:i:s');
            $att->last_seen = Carbon::createFromFormat('Y-m-d H:i:s', $att->last_seen, 'UTC')
            ->setTimezone('Asia/Kuala_Lumpur')
            ->format('Y-m-d H:i:s');
        }
        return response()->json([
            'data' => $attendance,
        ], 200);
    }

    public function getTimeline(Request $request)
    {
        // $request['date_range'] = "07/22/2021 - 07/22/2021";
        // $request['tag_mac'] = "AC233FA9C171";
        $date_start = new Carbon(explode(' - ', $request['date_range'])[0]);
        $date_end = new Carbon(explode(' - ', $request['date_range'])[1]);
        $date_end = $date_end->addDays(1)->subSecond(1);
     
        $attendance = json_decode(json_encode(Attendance_KLIA::whereBetween('date', [$date_start, $date_end])
        ->whereNotNull('staff_name')
        ->whereNotNull('location_name')
        ->where('tag_mac', $request['tag_mac'])
        ->orderBy('first_seen', 'desc')
        ->get()));

        $timeline_data = array();
        $timeline_data_merged = array();
        foreach ($attendance as $att){
            //$att->full_name = $att->tag->resident->resident_fName." ". $att->tag->resident->resident_lName;
            $data_object = (object)[];
            $data_object_merged = (object)[];
            $data_object->x = $att->location_name;
            $data_object_merged->x = substr($att->location_name, 0, strpos($att->location_name, " / "));
            $first_seen = ((int)Carbon::createFromFormat('Y-m-d H:i:s', $att->first_seen, 'UTC')
                                    //->setTimezone('Asia/Kuala_Lumpur') //Set timezone only changes the output of format() if it returns a string
                                    ->addHours(8) // Must instead add 8 hours to convert the underlying millisecond count to UTC+8
                                    ->format('Uu')) 
                                    / 1000;
            $last_seen = ((int) Carbon::createFromFormat('Y-m-d H:i:s', $att->last_seen, 'UTC')
                                    ->addHours(8)
                                    ->format('Uu'))
                                    / 1000;
            $data_object->y[0] = $first_seen;
            $data_object->y[1] = $last_seen;
            $data_object_merged->y[0] = $first_seen;
            $data_object_merged->y[1] = $last_seen;
            $att->time_missing = Carbon::parse($att->last_seen)->diffForHumans();
            array_push($timeline_data, $data_object);
            array_push($timeline_data_merged, $data_object_merged);
        }

        //The required series object for an ApexChart Timeline Series is
        //An Single Item Array containing
        //An Object with property Data
        //Data is an array of objects
        //Each object in Data has property X and Y
        //X is the vertical label (Location)
        //Y is the horizontal time label (Duration)
        $timeline = array();
        $timeline_object = (object)[];
        $timeline_object->data = $timeline_data;
        $timeline_merged = array();
        $timeline_object_merged = (object)[];
        $timeline_object_merged->data = $timeline_data_merged;
        array_push($timeline, $timeline_object);
        array_push($timeline_merged, $timeline_object_merged);
        return response()->json([
            'data' => $attendance,
            'timeline' => $timeline,
            'timeline_merged' => $timeline_merged,
        ], 200);
    }

    public function getSelect(Request $request)
    {
        $date = new Carbon($request['date_range']);
        //$date = new Carbon("07/14/2021");
        $attendance = Attendance_KLIA::where('date', $date)
        ->whereNotNull('staff_name')
        ->whereNotNull('location_name')
        ->orderBy('first_seen', 'desc')
        ->get();

        //Get an array of unique tag macs that attended within the date range
        $arr = array();
        foreach ($attendance as $key => $item) {
           $arr[$item['tag_mac']] = $item;
        }
        ksort($arr, SORT_NUMERIC);
     
        $select_object = (object)[];
        $select_object->results = array();
        $i = 0;
        foreach ($arr as $tag => $att){
            $object = (object)[];
            $object->id = $i;
            $object->text = $att->staff_name;
            $object->tag_mac = $tag;
            $select_object->results[$i] = $object;
            $i++;
        }
        return response()->json([
            'select' => $select_object,
        ], 200);
    }

    public function getAlerts(Request $request)
    {
        // $request['date_range'] = "01/01/2021 - 09/01/2021";
        $date_start = new Carbon(explode(' - ', $request['date_range'])[0]);
        $date_end = new Carbon(explode(' - ', $request['date_range'])[1]);
        $date_end = $date_end->addDays(1)->subSecond(1);
     
        $alerts = Alert::whereBetween('occured_at', [$date_start, $date_end])
        ->orderBy('alert_id', 'asc')
        ->with(['reader', 'policy', 'policy.policyType', 'tag', 'tag.resident', 'tag.user', 'user'])
        ->has('tag.resident')
        ->get();

        foreach ($alerts as $alert){
            $alert->full_name = $alert->tag->resident->resident_fName." ".$alert->tag->resident->resident_lName;
            if ($alert->resolved_at != null){
                $alert->status = true;
            }else{
                $alert->status = false;
            }
           
        }
        return response()->json([
            'data' => $alerts,
        ], 200);
    }
}
