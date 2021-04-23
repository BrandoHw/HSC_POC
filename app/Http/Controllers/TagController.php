<?php

namespace App\Http\Controllers;

use App\Resident;
use App\Tag;
use App\TagType;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class TagController extends Controller
{
    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    function __construct()
    {
        $this->middleware('permission:beacon-list|beacon-create|beacon-edit|beacon-delete', ['only' => ['index','edit']]);
        $this->middleware('permission:beacon-create', ['only' => ['create','store']]);
        $this->middleware('permission:beacon-edit', ['only' => ['update']]);
        $this->middleware('permission:beacon-delete', ['only' => ['destroy']]);
    }
    
    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function index()
    {
        $tags = Tag::orderBy('beacon_id', 'asc')->get();

        return view('tags.index',compact('tags'));
    }
    
    /**
    * Show the form for creating a new resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function create()
    {
        $users = User::doesntHave('tag')->orderBy('fName', 'asc')->get();
        $residents = Resident::doesntHave('tag')->orderBy('resident_fName', 'asc')->get();
        
        $available = true;
        if($users->isEmpty() && $residents->isEmpty()){
            $available = false;
        }
        return view('tags.create',compact('users', 'residents', 'available'));
    }
    
    /**
    * Store a newly created resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    public function store(Request $request)
    {
        if($request['assign'] == '1'){
            request()->validate([
                'beacon_mac' => 'required|string|min:12|max:12|unique:beacons_table,beacon_mac',
                'target' => 'required'
            ]);
        } else {
            request()->validate([
                'beacon_mac' => 'required|string|min:12|max:12|unique:beacons_table,beacon_mac',
            ]);
        }
        
        $tag = Tag::create($request->all());

        /** Save the wristband tag type */
        $tag_type = TagType::find(1);
        $tag->tagType()->associate($tag_type)->save();

        if(!empty($request['target'])){
            $target_type = explode('-', $request['target'])[0];
            $target_id = explode('-', $request['target'])[1];

            if($target_type == "R"){
                $resident = Resident::find($target_id);
                $resident->tag()->associate($tag)->save();
            } else {
                $user = User::find($target_id);
                $user->tag()->associate($tag)->save();
            }
        }
        
        return redirect()->route('beacons.index')
            ->with('success','Beacon added successfully.');
    }
    
    /**
    * Display the specified resource.
    *
    * @param  \App\Tag  $tag
    * @return \Illuminate\Http\Response
    */
    public function show(Tag $tag)
    {
        //
    }
    
    /**
    * Show the form for editing the specified resource.
    *
    * @param  \App\Tag  $tag
    * @return \Illuminate\Http\Response
    */
    public function edit(Tag $tag)
    {
        $users = User::doesntHave('tag')->get();
        $residents = Resident::doesntHave('tag')->get();

        $current = null;
        /** Concat the current user of this tag, if exist */
        if(!empty($tag->user)){
            $current = $tag->user;
            $users->push($current);
        }

        /** Concat the current resident of this tag, if exist */
        if(!empty($tag->resident)){
            $current = $tag->resident;
            $residents->push($current)->all();
        }

        $available = true;
        if($users->isEmpty() && $residents->isEmpty()){
            $available = false;
        }

        return view('tags.edit',compact('tag', 'users', 'current', 'residents', 'available'));
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
        if($request['assign'] == '1'){
            request()->validate([
                'beacon_mac' => 'required|string|min:12|max:12|unique:beacons_table,beacon_mac,'.$tag->beacon_id.',beacon_id',
                'target' => 'required'
            ]);
        } else {
            request()->validate([
                'beacon_mac' => 'required|string|min:12|max:12|unique:beacons_table,beacon_mac,'.$tag->beacon_id.',beacon_id',
            ]);
        }

        $tag->update($request->all());

        /** Remove the user/resident associated with this tag */
        if(!empty($tag->user)){
            $tag->user->tag()->dissociate()->save();
        }

        if(!empty($tag->resident)){
            $tag->resident->tag()->dissociate()->save();
        }

        if(!empty($request['target'])){
            $target_type = explode('-', $request['target'])[0];
            $target_id = explode('-', $request['target'])[1];

            if($target_type == "R"){
                $resident = Resident::find($target_id);
                $resident->tag()->associate($tag)->save();
            } else {
                $user = User::find($target_id);
                $user->tag()->associate($tag)->save();
            }
        }

        return redirect()->route('beacons.index')
            ->with('success','Beacon updated successfully');
    }
   
    /**
    * Remove the specified resource from storage.
    *
    * @param  \App\Tag  $tag
    * @return \Illuminate\Http\Response
    */
    public function destroy(Tag $tag)
    {
        //
    }

    /**
     * Remove the specified resources from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function destroys(Request $request)
    {
        $ids = $request->beacons_id;

        Tag::destroy($ids);

        if(count($ids) > 1){
            return response()->json([
                "success" => "Beacons deleted successfully."
            ], 200);
        } else {
            return response()->json([
                "success" => "Beacon deleted successfully."
            ], 200);
        }
    }

    public function userLastSeen(){
        // $userLastSeen = UserLastSeen::with('user')->get();
        // return $userLastSeen;
    }
}