<?php

namespace App\Http\Controllers;

use App\Attendance;
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
        $attendances = Attendance::latest()->paginate(5);
        return view('attendances.index',compact('attendances'))
            ->with('i', (request()->input('page', 1) - 1) * 5);
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