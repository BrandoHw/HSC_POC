<?php

namespace App\Http\Controllers;

use App\Attendance_KLIA;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

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
            ->format('Y-m-d g:i:s A');
            $att->last_seen = Carbon::createFromFormat('Y-m-d H:i:s', $att->last_seen, 'UTC')
            ->setTimezone('Asia/Kuala_Lumpur')
            ->format('Y-m-d g:i:s A');
        }
        return response()->json([
            'data' => $attendance,
        ], 200);
    }

    public function getTimeline(Request $request)
    {
        // $request['date_range'] = "07/09/2021 - 07/09/2021";
        // $request['tag_mac'] = "AC233FA8C2EE";
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
        foreach ($attendance as $att){
            //$att->full_name = $att->tag->resident->resident_fName." ". $att->tag->resident->resident_lName;
            $data_object = (object)[];
            $data_object->x = $att->location_name;
            $data_object->y[0] = ((int)Carbon::createFromFormat('Y-m-d H:i:s', $att->first_seen, 'UTC')
                                    //->setTimezone('Asia/Kuala_Lumpur') //Set timezone only changes the output of format() if it returns a string
                                    ->addHours(8) // Must instead add 8 hours to convert the underlying millisecond count to UTC+8
                                    ->format('Uu')) 
                                    / 1000;
            $data_object->y[1] = ((int) Carbon::createFromFormat('Y-m-d H:i:s', $att->last_seen, 'UTC')
                                    ->addHours(8)
                                    ->format('Uu'))
                                    / 1000;
            $att->time_missing = Carbon::parse($att->last_seen)->diffForHumans();
            array_push($timeline_data, $data_object);
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
        array_push($timeline, $timeline_object);
        return response()->json([
            'data' => $attendance,
            'timeline' => $timeline,
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
}
