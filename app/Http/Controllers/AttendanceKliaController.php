<?php

namespace App\Http\Controllers;

use App\Attendance_KLIA;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class AttendanceKliaController extends Controller
{
    public function index (){
        $attendance = Attendance_KLIA::latest('last_seen')->get();
        return view('klia.dashboard.index', compact('attendance'));
    }

    public function getAttendanceKLIA(Request $request)
    {
        // $request['date_range'] = "05/05/2021 - 06/06/2021";
        $date_start = new Carbon(explode(' - ', $request['date_range'])[0]);
        $date_end = new Carbon(explode(' - ', $request['date_range'])[1]);
        $date_end = $date_end->addDays(1)->subSecond(1);
     
        $attendance = json_decode(json_encode(Attendance_KLIA::with('tag', 'tag.resident', 'gateway', 'gateway.location')
                ->has('tag.resident')
                ->has('gateway.location')
                ->whereBetween('first_seen', [$date_start, $date_end])
                ->orderBy('first_seen', 'desc')
                ->get()));

        foreach ($attendance as $att){
            $att->full_name = $att->tag->resident->resident_fName." ". $att->tag->resident->resident_lName;
            $att->time_missing = Carbon::parse($att->last_seen)->diffForHumans();
        }
        return response()->json([
            'data' => $attendance,
        ], 200);
    }
}
