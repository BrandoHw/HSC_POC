<?php

namespace App\Http\Controllers;

use App\Building;
use App\Company;
use App\Floor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BuildingController extends Controller
{
    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    function __construct()
    {
        $this->middleware('permission:company-list|company-create|company-edit|company-delete', ['only' => ['index','show']]);
        $this->middleware('permission:company-create', ['only' => ['create','store']]);
        $this->middleware('permission:company-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:company-delete', ['only' => ['destroy']]);
    }
    
    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function index()
    {
        $buildings = Building::orderBy('id','asc')->get();
        return view('buildings.index',compact('buildings'));
    }
    
    /**
    * Show the form for creating a new resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function create()
    {
        return view('buildings.create');
    }
    
    /**
    * Store a newly created resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'floor_num' => 'required|integer|between:1,100',
            'address' => 'required',
            'lat' => 'required',
            'lng' => 'required',
        ]);
        
        if($validator->fails()){
            return response()->json([
                "errors" => $validator->errors()]);
        }

        $building = Building::create($request->all());
            
        foreach(range(1, $request['floor_num']) as $floorNum){
            $floor = Floor::create([
                'number' => $floorNum 
            ]);
            $building->floors()->save($floor);
        }

        if($request->has('company_id')){
            $company = Company::find($request['company_id']);
            $company->buildings()->save($building);
        }

        return response()->json([
            'success'=>'<strong>'.$building->name.'</strong> added succesfully.',
            "building" => $building], 
        200);
	}
    
    /**
    * Display the specified resource.
    *
    * @param  \App\Building  $building
    * @return \Illuminate\Http\Response
    */
    public function show(Building $building)
    {
        $floors = $building->floors;
        return response()->json([
            'floors' => $floors],
            200);
    }
    
    /**
    * Show the form for editing the specified resource.
    *
    * @param  \App\Building  $building
    * @return \Illuminate\Http\Response
    */
    public function edit(Building $building)
    {
        return view('buildings.edit',compact('building'));
    }
    
    /**
    * Update the specified resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  \App\Building  $building
    * @return \Illuminate\Http\Response
    */
    public function update(Request $request, Building $building)
    {
        request()->validate([
            'name' => 'required',
            'address' => 'required',
            'company_id' => 'required',
        ]);
        $building->update($request->all());
        return redirect()->route('buildings.index')
            ->with('success','Building updated successfully');
    }
   
    /**
    * Remove the specified resource from storage.
    *
    * @param  \App\Building  $building
    * @return \Illuminate\Http\Response
    */
    public function destroy(Building $building)
    {
        $building->delete();
        return response()->json([
            'success'=>'Building removed successfully!',
        ], 200);
    }
}