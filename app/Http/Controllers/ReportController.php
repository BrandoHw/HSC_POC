<?php

namespace App\Http\Controllers;

use App\Alert;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Reader;

class ReportController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:report-list|report-create|report-edit|report-delete', ['only' => ['index','store']]);
        $this->middleware('permission:report-create', ['only' => ['create','store']]);
        $this->middleware('permission:report-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:report-delete', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $date_start = Carbon::today()->subDays(6);
        $date_end = Carbon::tomorrow()->subSecond(1);
        $alerts = Alert::orderBy('alert_id', 'asc')
        ->whereBetween('occured_at', [$date_start, $date_end])
        ->get();


        $gateways = Reader::with('location')->get();

        $status_check = Carbon::now()->subMinutes(5);
        foreach($gateways as $reader){
            if ($reader->up_status >= $status_check){
                $reader->online = true;
            }else{
                $reader->online = false;
            }
        }
        return view('reports.index', compact('alerts', 'gateways'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function getData(Request $request){
        $date_start = new Carbon(explode(' - ', $request['date_range'])[0]);
        $date_end = new Carbon(explode(' - ', $request['date_range'])[1]);
        $date_end = $date_end->addDays(1)->subSecond(1);
     

        $alerts = json_decode(json_encode(Alert::with(['policy', 'policy.policyType', 'tag', 'tag.staff', 'reader', 'reader.location'])     
                ->has('tag.staff')
                ->whereBetween('occured_at', [$date_start, $date_end])
                ->get()));

                
        $alerts_r = json_decode(json_encode(Alert::with(['policy', 'policy.policyType', 'tag', 'tag.resident', 'tag.gateway', 'reader', 'reader.location']) 
                ->has('tag.resident')
                ->whereBetween('occured_at', [$date_start, $date_end])
                ->get()));

              
        $alerts = array_merge((array) $alerts, (array) $alerts_r);


        foreach ($alerts as $alert){
            if (property_exists($alert->tag, 'staff')){
                $alert->name = $alert->tag->staff->fName." ".$alert->tag->staff->lName;
            }
            elseif(property_exists($alert->tag, 'resident')){
                $alert->name = $alert->tag->resident->resident_fName." ".$alert->tag->resident->resident_lName;
            };   
        }   
        
        foreach($alerts as $alert){
            $alert->date = Carbon::parse($alert->occured_at)->setTimezone('Asia/Kuala_Lumpur')->format('Y-m-d');
            $alert->time = Carbon::parse($alert->occured_at)->setTimezone('Asia/Kuala_Lumpur')->format('H:i:s');
        }


        return response()->json([
            'data' => $alerts,
        ], 200);}
}
