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
        $this->middleware('permission:reader-list|reader-create|reader-edit|reader-delete', ['only' => ['index','show']]);
        $this->middleware('permission:reader-create', ['only' => ['create','store']]);
        $this->middleware('permission:reader-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:reader-delete', ['only' => ['destroy']]);
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
        $buildings = Building::pluck('name', 'id')->all();
        return view('readers.create', compact('buildings'));
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
            'serial' => 'required|unique:readers,serial',
            'uuid' => 'required|unique:readers,uuid',
            'mac_addr' => 'required|unique:readers,mac_addr',
        ]);
        
        $reader = Reader::create($request->all());
        
        return redirect()->route('readers.index')
            ->with('success','Reader created successfully.');
    }
    
    /**
    * Display the specified resource.
    *
    * @param  \App\Reader  $reader
    * @return \Illuminate\Http\Response
    */
    public function show(Reader $reader)
    {
        return view('readers.show',compact('reader'));
    }
    
    /**
    * Show the form for editing the specified resource.
    *
    * @param  \App\Reader  $reader
    * @return \Illuminate\Http\Response
    */
    public function edit($id)
    {
        $reader = Reader::where('id', $id)->get()[0];
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
            'serial' => 'required|unique:readers,serial,'.$reader->id,
            'mac_addr' => 'required|unique:readers,mac_addr,'.$reader->id,
        ]);
        $reader->update($request->all());
        return redirect()->route('readers.index')
            ->with('success','Reader updated successfully');
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

    
    function console_log( $data ){
        echo '<script>';
        echo 'console.log('. json_encode( $data ) .')';
        echo '</script>';
      }
}