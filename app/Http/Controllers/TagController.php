<?php

namespace App\Http\Controllers;

use App\Tag;
use App\User;
use Illuminate\Http\Request;

class TagController extends Controller
{
    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    function __construct()
    {
        $this->middleware('permission:tag-list|tag-create|tag-edit|tag-delete', ['only' => ['index','show']]);
        $this->middleware('permission:tag-create', ['only' => ['create','store']]);
        $this->middleware('permission:tag-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:tag-delete', ['only' => ['destroy']]);
    }
    
    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function index()
    {
        $tags = Tag::latest()->get();
        return view('tags.index',compact('tags'));
    }
    
    /**
    * Show the form for creating a new resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function create()
    {
        return view('tags.create');
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
            'serial' => 'required|unique:tags,serial',
            'uuid' => 'required|unique:tags,uuid',
            'mac_addr' => 'required|unique:tags,mac_addr',
        ]);
        
        $tag = Tag::create($request->all());
        
        return redirect()->route('tags.index')
            ->with('success','Tag created successfully.');
    }
    
    /**
    * Display the specified resource.
    *
    * @param  \App\Tag  $tag
    * @return \Illuminate\Http\Response
    */
    public function show(Tag $tag)
    {
        return view('tags.show',compact('tag'));
    }
    
    /**
    * Show the form for editing the specified resource.
    *
    * @param  \App\Tag  $tag
    * @return \Illuminate\Http\Response
    */
    public function edit(Tag $tag)
    {
        return view('tags.edit',compact('tag'));
    }
    
    /**
    * Update the specified resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  \App\Tag  $tag
    * @return \Illuminate\Http\Response
    */
    public function update(Request $request, Tag $tag)
    {
        request()->validate([
            'serial' => 'required|unique:tags,serial,'.$tag->id,
            'mac_addr' => 'required|unique:tags,mac_addr,'.$tag->id,
        ]);
        $tag->update($request->all());
        return redirect()->route('tags.index')
            ->with('success','Tag updated successfully');
    }
   
    /**
    * Remove the specified resource from storage.
    *
    * @param  \App\Tag  $tag
    * @return \Illuminate\Http\Response
    */
    public function destroy(Tag $tag)
    {
        $tag->delete();
        return redirect()->route('tags.index')
          ->with('success','Tag deleted successfully');
    }
}