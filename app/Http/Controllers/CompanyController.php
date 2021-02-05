<?php

namespace App\Http\Controllers;

use App\Building;
use App\Company;
use App\Floor;
use App\Reader;
use App\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CompanyController extends Controller
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
        $companies = Company::orderBy('id', 'asc')->get();
        $readersNum = 0;
        return view('companies.index',compact('companies', 'readersNum'));
    }
    
    /**
    * Show the form for creating a new resource.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    public function create()
    {
        $buildingsId = Building::doesntHave('company')->pluck('id')->all();
        Building::destroy($buildingsId);
        return response()->json([
            'success' => 'Building reset successfully.'
        ], 200);
    }
    
    /**
    * Store a newly created resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    public function store(Request $request)
    {
        if($request->has('validate')){
            $validator = Validator::make($request->all(), [
                'name' => 'required|unique:companies,name'
            ]);
    
            if($validator->fails()){
                return response()->json([
                    "errors" => $validator->errors()]);
            }
            return response()->json([
                'success'=>'No errors.'], 
            200);
        }
        else{
            $company = Company::create($request->all());
            $readersNum = 0;

            foreach($request['buildingsId'] as $buildingId){
                $building = Building::find($buildingId);
                $company->buildings()->save($building);
                $readersNum += $building->readers()->count();
            }
    
            return response()->json([
                'company' => $company,
                'buildings' => $company->buildings,
                'buildingsNum' => $company->buildings->count(),
                'readersNum' => $readersNum,
                'success' => '<strong>'.$company->name.'</strong> created successfully!'
            ], 200);
        }
    }
    
    /**
    * Display the specified resource.
    *
    * @param  \App\Company  $company
    * @return \Illuminate\Http\Response
    */
    public function show(Company $company)
    {
        $buildings = $company->buildings->pluck('name', 'id')->all();
        $readersNull = Reader::doesntHave('floor')->get();
        $id = $company->buildings->pluck('id')->all();
        $floors = Floor::whereIn('building_id', $id)->with('map')->get();

		return view('companies.show', compact('readersNull', 'buildings', 'company', 'floors'));
    }
    
    /**
    * Show the form for editing the specified resource.
    *
    * @param  \App\Company  $company
    * @return \Illuminate\Http\Response
    */
    public function edit(Company $company)
    {
    }
    
    /**
    * Update the specified resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  \App\Company  $company
    * @return \Illuminate\Http\Response
    */
    public function update(Request $request, Company $company)
    {
        request()->validate([
            'name' => 'required',
        ]);
        $company->update($request->all());
        return redirect()->route('companies.index')
            ->with('success','Company updated successfully');
    }
   
    /**
    * Remove the specified resource from storage.
    *
    * @param  \App\Company  $company
    * @return \Illuminate\Http\Response
    */
    public function destroy(Company $company)
    {
        $company->delete();
        return response()->json([
            'success'=>'Company removed successfully!',
        ], 200);
    }

    function console_log( $data ){
        echo '<script>';
        echo 'console.log('. json_encode( $data ) .')';                                                                                                                     
        echo '</script>';
      }
}