<?php

namespace App\Http\Controllers;

use App\Building;
use App\Company;
use App\Floor;
use App\Reader;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class CompanyReaderController extends Controller
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
     * @param \App\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function index(Company $company)
    {
        $buildings = $company->buildings->pluck('name', 'id')->all();
        $readersNull = Reader::doesntHave('floor')->get();
        
		return view('companyReaders.index', compact('readersNull', 'buildings', 'company'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param \App\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function create(Company $company)
    {
        $readersNull = Reader::doesntHave('floor')->get();
        $refinedReadersNull = collect();
        foreach($readersNull as $reader){
            $data = collect($reader);
            $data->put('rowId', 'trNullReader-'.$reader->id);
            $refinedReadersNull->push($data);
        }
        
        $refinedCompany = collect($company);
        $refinedCompany->put('buildings', $company->buildings);
        
		return response()->json([
            'readersNull' => $refinedReadersNull,
            'company' => $refinedCompany],
            200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param \App\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Company $company)
    {
        foreach($request['readers'] as $readerInfo){
            $reader = Reader::find($readerInfo['id']);
            $floor = Floor::find($readerInfo['floorId']);
            $floor->readers()->save($reader);
        }

        $readersNull = Reader::doesntHave('floor')->get();
        
        return response()->json([
            'readersNull' => $readersNull,
            'success' => 'Readers added successfully!'
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function show(Company $company)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Company  $company
     * @param \App\Reader  $reader
     * @return \Illuminate\Http\Response
     */
    public function edit(Company $company, Reader $reader)
    {
        $refinedReader = collect($reader);
        $refinedReader->put('building', $reader->floor->building);

        $buildings = $company->buildings;
        $floors = $reader->floor->building->floors;

        return response()->json([
            'reader' => $refinedReader,
            'buildings' => $buildings,
            'floors' => $floors,
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param \App\Company  $company
     * @param \App\Reader  $reader
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Company $company, Reader $reader)
    {
        $floor = Floor::find($request['floorId']);
        $reader->floor()->associate($floor)->save();

        $refinedReader = collect($reader);
        $refinedReader->put('building', $reader->floor->building);
        
        return response()->json([
            'reader' => $refinedReader,
            'success' => '<strong>'.$reader->serial.'</strong> updated successfully!' 
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Company  $company
     * @param \App\Reader  $reader
     * @return \Illuminate\Http\Response
     */
    public function destroy(Company $company, Reader $reader)
    {
        $reader->floor()->dissociate()->save();
        $readersNull = Reader::doesntHave('floor')->get();

        return response()->json([
            'reader' => $reader,
            'readersNull' => $readersNull,
            'success' => '<strong>'.$reader->serial.'</strong> removed successfully!' 
        ], 200);
    }

    function console_log( $data ){
        echo '<script>';
        echo 'console.log('. json_encode( $data ) .')';                                                                                                                     
        echo '</script>';
      }
}
