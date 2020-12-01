<?php

namespace App\Http\Controllers;

use App\Building;
use App\Company;
use App\Group;
use App\Schedule;
use App\Timeblocks;
use Illuminate\Http\Request;
use App\Services\CalendarService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use App\Http\Requests\AddTimeblockRequest;
use App\Rules\TimeblockAvailablility;
use App\Reader;
class TimeblockController extends Controller
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
        //


    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  App\Group  $group
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request, Group $group)
    {
        $companies = Company::pluck('name', 'id')->all();
        
        $company = Company::find($request['companyId']);
        $buildings = collect();

        foreach($company->buildings as $building){
            $data = collect([
                'id' => $building->id,
                'text' => $building->name
            ]);
            $buildings->push($data);
        }
        return response()->json([
            'companies' => $companies,
            'buildings' =>$buildings
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  App\Group $group
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Group $group)
    {
        $validator = Validator::make($request->all(), [
            'schedule_id' => 'required',
            'building_id' => 'required',
            'company_id' => 'required',
            'day' => 'required|int|between:1,7',
            'start_time' => ['required',
                new TimeblockAvailablility(),
                'date_format:H:i'],
            'end_time'   => [
                'required',
                'after:start_time',
                'date_format:H:i']
        ]);
                
        if($validator->fails()){
            return response()->json([
                'errors' => $validator->errors()]);
        }else{
            $timeblock = Timeblocks::create($request->all());

            $schedule = Schedule::find($request['schedule_id']);
            $schedule->timeblocks()->save($timeblock);

            $company = Company::find($request['company_id']);
            $company->timeblocks()->save($timeblock);

            $building = Building::find($request['building_id']);
            $building->timeblocks()->save($timeblock);

            return response()->json([
                'success' => 'Schedule updated!'],
            200);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(CalendarService $calendarService, $id)
    {
        //
        $weekDays     = Timeblocks::WEEK_DAYS;
        $calendarData = $calendarService->generateCalendarData($weekDays, $id);
        $buildings = Building::pluck('name', 'id')->all();
        return view('timeblocks.show', compact('weekDays', 'calendarData', 'buildings'));
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
     * Remove the specified resource from storage.
     *
     * @param  App\Group $group
     * @param  App\Timeblocks $timeblock
     * @return \Illuminate\Http\Response
     */
    public function destroy(Group $group, Timeblocks $timeblock)
    {
        $timeblock->schedule()->dissociate()->save();
        $timeblock->company()->dissociate()->save();
        $timeblock->building()->dissociate()->save();
        // $timeblock->delete();

        // $this->console_log($id);
        // $timeblock =  DB::table('timeblocks')->where('id', $id)->get();
        // $this->console_log("id".$timeblock[0]->id);
        // DB::table('timeblocks')->where('id', $id)->delete();

        // $weekDays     = Timeblocks::WEEK_DAYS;
        // $calendarData = $calendarService->generateCalendarData($weekDays, $timeblock[0]->schedule_id);
        // $readers = Reader::all();
        // return view('timeblocks.show', compact('weekDays', 'calendarData', 'readers'));
    
        return response()->json([
            'success' => 'Timeblock deleted!'
        ], 200);
    }

    function console_log( $data ){
        echo '<script>';
        echo 'console.log('. json_encode( $data ) .')';
        echo '</script>';
      }
}
