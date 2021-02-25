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
        /** Get tag type id and combine with their name, ['id' => 'type_name']*/
        $tagTypes = TagType::pluck('beacon_type_id');
        $tagTypes = $tagTypes->combine(['Card', 'Wristband'])->all();

        /** Get users & residents that doesnt have tag() ['id' => 'full_name'] */
        $usersNull = User::doesntHave('tag')->get()->pluck('full_name', 'user_id');
        $residentsNull = Resident::doesntHave('tag')->get()->pluck('full_name', 'resident_id');

        return view('tags.create',compact('tagTypes', 'usersNull', 'residentsNull'));
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
            'beacon_mac' => 'required|unique:beacons_table,beacon_mac,',
            'beacon_type' => 'required',
        ]);
        
        $tag = Tag::create($request->all());

        /** Save the tag type */
        $tagType = TagType::find($request['beacon_type']);
        $tag->tagType()->associate($tagType)->save();

        /** If beacon is card, save user. If beacon is wristband, save resident*/
        if($request['beacon_type'] == "1"){
            if(!empty($request['user_id'])){
                $user = User::find($request['user_id']);
                $user->tag()->associate($tag)->save();
            }
        } else {
            if(!empty($request['resident_id'])){
                $resident = Resident::find($request['resident_id']);
                $resident->tag()->associate($tag)->save();
            }
        }
        
        return redirect()->route('beacons.index')
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
        /** Get tag type id and combine with their name, ['id' => 'type_name']*/
        $tagTypes = TagType::pluck('beacon_type_id');
        $tagTypes = $tagTypes->combine(['Card', 'Wristband'])->all();

        /** Get users & residents that doesnt have tag() ['id' => 'full_name'] */
        $usersNull = User::doesntHave('tag')->get()->pluck('full_name', 'user_id');
        $residentsNull = Resident::doesntHave('tag')->get()->pluck('full_name', 'resident_id');

        /** Concat the current user of this tag, if exist */
        if(!empty($tag->user)){
            $current = collect([$tag->user->user_id => $tag->user->full_name." [Current]"]);
            $usersNull = $current->concat($usersNull)->all();
        }

        /** Concat the current resident of this tag, if exist */
        if(!empty($tag->resident)){
            $current = collect([$tag->resident->resident_id => $tag->resident->full_name." [Current]"]);
            $residentsNull = $current->concat($residentsNull)->all();
        }
        return view('tags.edit',compact('tag', 'tagTypes', 'usersNull', 'residentsNull'));
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
            'beacon_mac' => 'required|unique:beacons_table,beacon_mac,'.$tag->id,
            'beacon_type' => 'required',
        ]);
        $tag->update($request->all());
        
        /** Save the tag type */
        if(!empty($tag->tagType)){
            $tag->tagType()->dissociate()->save();
            $tagType = TagType::find($request['beacon_type']);
            $tag->tagType()->associate($tagType)->save();
        }
        
        /** Remove the user/resident associated with this tag */
        if(!empty($tag->user)){
            $tag->user->tag()->dissociate()->save();
        }

        if(!empty($tag->resident)){
            $tag->resident->tag()->dissociate()->save();
        }

        /** If beacon is card, save user. If beacon is wristband, save resident*/
        if($request['beacon_type'] == "1"){
            if(!empty($request['user_id'])){
                $user = User::find($request['user_id']);
                $user->tag()->associate($tag)->save();
            }
        } else {
            if(!empty($request['resident_id'])){
                $resident = Resident::find($request['resident_id']);
                $resident->tag()->associate($tag)->save();
            }
        }

        return redirect()->route('beacons.index')
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