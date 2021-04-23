<?php

namespace App\Http\Controllers;

use App\Resident;
use App\Tag;
use Illuminate\Http\Request;

class ResidentController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:resident-list|resident-create|resident-edit|resident-delete', ['only' => ['index','edit']]);
        $this->middleware('permission:resident-create', ['only' => ['create','store']]);
        $this->middleware('permission:resident-edit', ['only' => ['update']]);
        $this->middleware('permission:resident-delete', ['only' => ['destroys']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $residents = Resident::orderBy('resident_id', 'asc')->get();
        return view('residents.index', compact('residents'));
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
     * @param  \App\Resident  $resident
     * @return \Illuminate\Http\Response
     */
    public function edit(Resident $resident)
    {
        $tags = Tag::doesntHave('resident')
            ->doesntHave('user')
            ->pluck('beacon_mac', 'beacon_id');
        
        if(!empty($resident->tag)){
            $current = collect([$resident->tag->beacon_id => $resident->tag->beacon_mac]);
            $tags= $current->concat($tags)->all();
        }
        return view('residents.edit', compact('resident', 'tags'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Tag  $resident
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Resident $resident)
    {
        request()->validate([
            'beacon_id' => 'required|',
        ]);

        if(!empty($resident->tag)){
            $resident->tag()->dissociate()->save();
        }

        $tag = Tag::find($request['beacon_id']);
        $resident->tag()->associate($tag)->save();

        return redirect()->route('residents.index')
            ->with('success','Resident updated successfully');
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
