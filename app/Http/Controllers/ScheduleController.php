<?php

namespace App\Http\Controllers;

use App\Schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Group;

class ScheduleController extends Controller
{
    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    function __construct()
    {
        $this->middleware('permission:group-list|group-create|group-edit|group-delete', ['only' => ['index','show']]);
        $this->middleware('permission:group-create', ['only' => ['create','store']]);
        $this->middleware('permission:group-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:group-delete', ['only' => ['destroy']]);
    }
    
    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function index()
    {
        $schedules = Schedule::orderBy('id', 'asc')->get();
        return view('schedules.index',compact('schedules'));
    }
    
    /**
    * Show the form for creating a new resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function create()
    {
        return view('schedules.create');
    }
    
    /**
    * Store a newly created resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    public function store(Request $request)
    {
        request()->validate([
            'name' => 'required|unique:schedules, name',
        ]);
        Schedule::create($request->all());
        return redirect()->route('schedules.index')
            ->with('success','Schedule created successfully.');
    }
    
    /**
    * Display the specified resource.
    *
    * @param  \App\Schedule  $schedule
    * @return \Illuminate\Http\Response
    */
    public function show(Schedule $schedule)
    {
        return view('schedules.show',compact('schedule'));
    }
    
    /**
    * Show the form for editing the specified resource.
    *
    * @param  \App\Schedule  $schedule
    * @return \Illuminate\Http\Response
    */
    public function edit(Schedule $schedule)
    {
        return view('schedules.edit',compact('schedule'));
    }
    
    /**
    * Update the specified resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  \App\Schedule  $schedule
    * @return \Illuminate\Http\Response
    */
    public function update(Request $request, Schedule $schedule)
    {
        request()->validate([
            'name' => 'required',
           
        ]);
        $schedule->update($request->all());
        return redirect()->route('schedules.index')
            ->with('success','Schedule updated successfully');
    }
   
    /**
    * Remove the specified resource from storage.
    *
    * @param  \App\Schedule  $schedule
    * @return \Illuminate\Http\Response
    */
    public function destroy(Schedule $schedule)
    {

        //Delete all timeblocks that reference schedule first
        DB::table('timeblocks')->where('schedule_id', $schedule->id)->delete();
        $schedule->delete();
        return redirect()->route('schedules.index')
          ->with('success','Schedule deleted successfully');
    }
}