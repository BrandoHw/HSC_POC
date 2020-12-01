<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Faker\Generator as Faker;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = collect();
        $addData = collect(['name' => 'Alpha 1', 'company' => 'Petronas', 'building' => 'Tower 3', 'start_time' => '08:00', 'end_time' => '10:00']);
        $data->push($addData);
        $addData = collect(['name' => 'Alpha 2', 'company' => 'Shell', 'building' => 'Tower 1', 'start_time' => '08:00', 'end_time' => '11:00']);
        $data->push($addData);
        $addData = collect(['name' => 'Alpha 3', 'company' => 'Caltex', 'building' => 'Tower 7', 'start_time' => '08:00', 'end_time' => '12:00']);
        $data->push($addData);
        $addData = collect(['name' => 'Alpha 4', 'company' => 'Shell', 'building' => 'Tower 4', 'start_time' => '10:00', 'end_time' => '13:00']);
        $data->push($addData);
        $addData = collect(['name' => 'Alpha 5', 'company' => 'Petron', 'building' => 'Tower 5', 'start_time' => '12:00', 'end_time' => '15:00']);
        $data->push($addData);
        $addData = collect(['name' => 'Alpha 6', 'company' => 'BHP', 'building' => 'Tower 8', 'start_time' => '13:00', 'end_time' => '17:00']);
        $data->push($addData);
        $addData = collect(['name' => 'Alpha 7', 'company' => 'Caltex', 'building' => 'Tower A', 'start_time' => '15:00', 'end_time' => '18:00']);
        $data->push($addData);
        
        return response()->json([
            'data' => $data
        ], 200);
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
}
