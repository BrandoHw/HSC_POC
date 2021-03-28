<?php

namespace App\Http\Controllers;

use App\Alert;
use Illuminate\Http\Request;

class AlertController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $alerts = Alert::orderBy('alert_id', 'asc')->get();
        return view('alerts.index', compact('alerts'));
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
         //Get all Tag where gateway's mac is equal to the request mac_addr and which have an associated resident/staff
         if ($id === 'unresolved'){
            $alert_s = 
                Alert::whereHas('tag', function($q){
                    $q->whereHas('staff');})
                    ->with(['tag', 'tag.staff', 'reader', 'reader.location',  'policy'])
                    ->where('action', '==', 0)
                    ->get()
            ;

            foreach($alert_s as $alert){
                $alert->full_name = $alert->tag->staff->fName." ".$alert->tag->staff->lName;
            }

            $alert_r =
                Alert::whereHas('tag', function($q){
                    $q->whereHas('resident');})
                    ->with(['tag', 'tag.resident', 'reader', 'reader.location',  'policy'])
                    ->get()
            ;

            foreach($alert_r as $alert){
                $alert->full_name = $alert->tag->resident->resident_fName." ".$alert->tag->resident->resident_lName;
            }

            $alert_s = json_decode(json_encode($alert_s));
            $alert_r = json_decode(json_encode($alert_r));
            $alerts = array_merge((array) $alert_s, (array) $alert_r);
        }else{
            $alerts = Alert::where('alert_id', '==', $id)->get();
        }

        return $alerts;//[$alerts, $alert_c];
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
}
