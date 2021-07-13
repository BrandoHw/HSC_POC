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
     
        $attendance = json_decode(json_encode(Attendance_KLIA::whereBetween('first_seen', [$date_start, $date_end])
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
        $request['date_range'] = "08/07/2021 - 08/07/2021";
        $request['tag_mac'] = "AC233FA8C2EE";
        $date_start = new Carbon(explode(' - ', $request['date_range'])[0]);
        $date_end = new Carbon(explode(' - ', $request['date_range'])[1]);
        $date_end = $date_end->addDays(1)->subSecond(1);
     
        $attendance = json_decode(json_encode(Attendance_KLIA::whereBetween('first_seen', [$date_start, $date_end])
        ->whereNotNull('staff_name')
        ->whereNotNull('location_name')
        ->orderBy('first_seen', 'desc')
                ->get()));

        foreach ($attendance as $att){
            //$att->full_name = $att->tag->resident->resident_fName." ". $att->tag->resident->resident_lName;
            $att->time_missing = Carbon::parse($att->last_seen)->diffForHumans();
        }
        return response()->json([
            'data' => $attendance,
        ], 200);
    }
}
