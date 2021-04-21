<?php

namespace App\Http\Controllers;

use App\Building;
use App\Floor;
use App\Reader;
use Illuminate\Http\Request;

class ReaderController extends Controller
{
    /**
    * Display a listing of the resource. 
    *
    * @return \Illuminate\Http\Response
    */
    function __construct()
    {
        $this->middleware('permission:gateway-list|gateway-create|gateway-edit|gateway-delete', ['only' => ['index','show']]);
        $this->middleware('permission:gateway-create', ['only' => ['create','store']]);
        $this->middleware('permission:gateway-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:gateway-delete', ['only' => ['destroy']]);
    }
    
    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function index()
    {
        $readers = Reader::orderBy('gateway_id', 'asc')
                    ->get();

        return view('readers.index',compact('readers'));
    }
    
    /**
    * Show the form for creating a new resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function create()
    {
        return view('readers.create');
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
            'serial' => 'required|unique:gateways_table,serial',
            'mac_addr' => 'required|string|min:12|max:12|unique:gateways_table,mac_addr',
        ]);
        
        $reader = Reader::create($request->all());
        
        return redirect()->route('gateways.index')
            ->with('success','Gateway added successfully.');
    }
    
    /**
    * Display the specified resource.
    *
    * @param  \App\Reader  $reader
    * @return \Illuminate\Http\Response
    */
    public function show(Reader $reader)
    {
        //
    }
    
    /**
    * Show the form for editing the specified resource.
    *
    * @param  \App\Reader  $reader
    * @return \Illuminate\Http\Response
    */
    public function edit($id)
    {
        $reader = Reader::where('gateway_id', $id)->get()[0];
        $this->console_log($reader);
        return view('readers.edit',compact('reader'));
    }
    
    /**
    * Update the specified resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  \App\Reader  $reader
    * @return \Illuminate\Http\Response
    */
    public function update(Request $request, Reader $reader)
    {
        request()->validate([
            'serial' => 'required|unique:gateways_table,serial,'.$reader->gateway_id.',gateway_id',
            'mac_addr' => 'required|string|min:12|max:12|unique:gateways_table,mac_addr,'.$reader->gateway_id.',gateway_id',
        ]);
        $reader->update($request->all());
        return redirect()->route('gateways.index')
            ->with('success','Gateway updated successfully');
    }
   
    /**
    * Remove the specified resource from storage.
    *
    * @param  \App\Reader  $reader
    * @return \Illuminate\Http\Response
    */
    public function destroy(Reader $reader)
    {
        $reader->delete();
        return redirect()->route('readers.index')
          ->with('success','Reader deleted successfully');
    }

    /**
     * Remove the specified resources from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function destroys(Request $request)
    {
        $ids = $request->gateways_id;

        Reader::destroy($ids);

        if(count($ids) > 1){
            return response()->json([
                "success" => "Gateways deleted successfully."
            ], 200);
        } else {
            return response()->json([
                "success" => "Gateway deleted successfully."
            ], 200);
        }
    }
    
    function console_log( $data ){
        echo '<script>';
        echo 'console.log('. json_encode( $data ) .')';
        echo '</script>';
      }
}