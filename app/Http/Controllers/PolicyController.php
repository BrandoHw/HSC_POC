<?php

namespace App\Http\Controllers;

use App\Policy;
use Illuminate\Http\Request;

class PolicyController extends Controller
{
    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    function __construct()
    {
        $this->middleware('permission:policy-list|policy-create|policy-edit|policy-delete', ['only' => ['index','show']]);
        $this->middleware('permission:policy-create', ['only' => ['create','store']]);
        $this->middleware('permission:policy-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:policy-delete', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $policies = collect([
        //     collect(['id' => 1, 'name' => 'Laundry Room', 'type' => 'Geofence', 'applied_to' => 'All Beacons', 'created_at' => '1/2/2021 1:01:45 PM', 'state' => 'Active']),
        //     collect(['id' => 2, 'name' => 'Room 101', 'type' => 'Geofence', 'applied_to' => 'Custom Beacons', 'created_at' => '1/2/2021 1:03:25 PM', 'state' => 'Inactive']),
        //     collect(['id' => 3, 'name' => 'Low Battery', 'type' => 'Battery', 'applied_to' => 'All Beacons', 'created_at' => '2/2/2021 10:34:13 AM', 'state' => 'Active']),
        //     collect(['id' => 4, 'name' => 'Laundry Room', 'type' => 'Motion', 'applied_to' => 'All Beacons', 'created_at' => '2/2/2021 11:56:43 AM', 'state' => 'Inactive']),
        //     collect(['id' => 5, 'name' => 'Fall Detection', 'type' => 'Motion', 'applied_to' => 'All Beacons', 'created_at' => '3/2/2021 9:10:13 AM', 'state' => 'Active']),
        //     collect(['id' => 6, 'name' => 'Motionless', 'type' => 'Motion', 'applied_to' => 'All Beacons', 'created_at' => '3/2/2021 4:36:51 PM', 'state' => 'Active'])
        // ]);

        $policies = Policy::orderBy('rules_id', 'asc')->get();
        return view('policies.index', compact('policies'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // $residents = collect([
        //         collect(['id' => 1, 'name' => 'Anna Sthesia', 'beacon' => 'AC:23:DF:34']),
        //         collect(['id' => 2, 'name' => 'Dan Druff', 'beacon' => 'AC:23:DF:18']),
        //         collect(['id' => 3, 'name' => 'Hans Olo', 'beacon' => 'AC:23:DF:32']),
        //         collect(['id' => 4, 'name' => 'Lynn Guini', 'beacon' => 'AC:23:DF:24']),
        //         collect(['id' => 5, 'name' => 'Tim Burge', 'beacon' => 'AC:23:DF:14']),
        //         collect(['id' => 6, 'name' => 'Paul Molive', 'beacon' => 'AC:23:DF:14']),
        //         collect(['id' => 7, 'name' => 'Barb Dwyer', 'beacon' => 'AC:23:DF:14']),
        //         collect(['id' => 8, 'name' => 'Eric Shun', 'beacon' => 'AC:23:DF:14']),
        //         collect(['id' => 9, 'name' => 'Bill Dabear', 'beacon' => 'AC:23:DF:39']),
        //         collect(['id' => 10, 'name' => 'Marge Arita', 'beacon' => 'AC:23:DF:39'])
        // ]);
        return view('policies.create');
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
     * @param  App\Policy  $policy
     * @return \Illuminate\Http\Response
     */
    public function edit(Policy $policy)
    {
        $policyType = "";
        switch($policy->policyType->rules_type_id){
            case '1':
                $policyType = "_attendance";
                break;

        }
        return view('policies.edit'.$policyType, compact('policy'));
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
