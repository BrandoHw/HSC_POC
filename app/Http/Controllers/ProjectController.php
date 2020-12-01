<?php

namespace App\Http\Controllers;

use App\Project;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class ProjectController extends Controller
{
    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    function __construct()
    {
        $this->middleware('permission:project-list|project-create|project-edit|project-delete', ['only' => ['index','show']]);
        $this->middleware('permission:project-create', ['only' => ['create','store']]);
        $this->middleware('permission:project-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:project-delete', ['only' => ['destroy']]);
    }
    
    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function index()
    {
        $projects = Project::orderBy('id', 'asc')->get();
        return view('projects.index',compact('projects'));
    }
    
    /**
    * Show the form for creating a new resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function create()
    {
        return view('projects.create');
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
            'name' => 'required|unique:projects,name',
            'start_date' => 'required',
            'end_date' => 'required',
        ]);
	
        $project = Project::create($request->all());
        
		return redirect()->route('projects.index')->with('success','Project created successfully.');
	}
    
    /**
    * Display the specified resource.
    *
    * @param  \App\Project  $project
    * @return \Illuminate\Http\Response
    */
    public function show(Project $project)
    {
        return view('projects.show',compact('project'));
    }
    
    /**
    * Show the form for editing the specified resource.
    *
    * @param  \App\Project  $project
    * @return \Illuminate\Http\Response
    */
    public function edit(Project $project)
    {
        return view('projects.edit',compact('project'));
    }
    
    /**
    * Update the specified resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  \App\Project  $project
    * @return \Illuminate\Http\Response
    */
    public function update(Request $request, Project $project)
    {
        request()->validate([
            'name' => 'required|unique:projects,name,'.$project->id,
            'start_date' => 'required',
            'end_date' => 'required',
        ]);
		
        $project->update($request->all());
		
		return redirect()->route('projects.index')->with('success','Project updated successfully');
		
    }
   
    /**
    * Remove the specified resource from storage.
    *
    * @param  \App\Project  $project
    * @return \Illuminate\Http\Response
    */
    public function destroy(Project $project)
    {
        $project->delete();
		
        return redirect()->route('projects.index')->with('success','Project deleted successfully');
    }
}