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
        $attendance_policies = Policy::where('rules_type_id', '1')->orderBy('description', 'asc')->get();
        $attendance_alerts= Alert::orderBy('occured_at', 'asc')->whereIn('rules_id', $attendance_policies)->get();
        $targets = Tag::get();
        $minDate = $attendance_alerts->first()->occured_at_tz ?? Carbon::now('Asia/Kuala_Lumpur')->setTime(0,0,0)->setTimeZone('UTC');
        $maxDate = $attendance_alerts->last()->occured_at_tz ?? Carbon::now('Asia/Kuala_Lumpur')->setTime(0,0,0)->setTimeZone('UTC');
        return view('attendances.index',compact('attendance_alerts', 'attendance_policies', 'targets', 'minDate', 'maxDate'));
    }
    
    /**
    * Display the specified resource.
    *
    * @param  \App\Attendance  $attendance
    * @return \Illuminate\Http\Response
    */
    public function show(Attendance $attendance)
    {
        return view('attendances.show',compact('attendance'));
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