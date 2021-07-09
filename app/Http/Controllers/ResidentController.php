<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddResidentRequest;
use App\Http\Requests\UpdateResidentRequest;
use App\Location;
use App\Resident;
use App\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
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
        $residents = Resident::orderBy('resident_id', 'asc')->with('tag', 'room')->get();
        foreach($residents as $resident){
            $resident->resized_url = Storage::disk('s3-resized')->url("resized-".$resident->image_url);
        }
        return view('residents.index', compact('residents'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $tagsNull = Tag::doesntHave('resident')
            ->doesntHave('user')
            ->pluck('beacon_mac', 'beacon_id');
        
        $available = true;
        if($tagsNull->isEmpty()){
            $available = false;
        }
        
        $relationship = Resident::relationship;

        $rooms_ori = Location::where('location_description', 'like', 'Room _')->get();
        $rooms = [];
        foreach($rooms_ori as $room){
            $id = $room->location_master_id;
            $name = 'L'.$room->floor.' - '.$room->location_description;
            $rooms[$id] = $name;
        }

        return view('residents.create',compact('tagsNull', 'available', 'relationship', 'rooms'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AddResidentRequest $request)
    {
        
        $resident = Resident::create($request->all());

        $room = Location::find($request['location_room_id']);
        $resident->room()->associate($room)->save();
        
        if(!empty($request['beacon_id'])){
            $tag = Tag::find($request['beacon_id']);
            $resident->tag()->associate($tag)->save();
        }
        $image_id = "image-input";
    
        if ($request->hasFile($image_id)) {
            $extension = $request[$image_id]->extension();
            $filename = "resident-".$resident->resident_id.".".$extension;
            if ($request->file($image_id)->isValid()) {
                $validated = $request->validate([
                    'image-input' => 'mimes:jpeg,png|max:16384',
                ]);
                $image_url = Storage::disk('s3')->putFileAs(
                'residents', $request->file('image-input'), $filename,
                );
                $resident->update(['image_url' => $image_url]);
            }
        }

        $tagsNull = Tag::doesntHave('resident')
            ->doesntHave('user')
            ->pluck('beacon_mac', 'beacon_id');
        
        $current = null;
        if(!empty($resident->tag)){
            $current = collect([$resident->tag->beacon_id => $resident->tag->beacon_mac]);
            $tagsNull = $current->concat($tagsNull);
        }

        $available = true;
        if($tagsNull->isEmpty()){
            $available = false;
        }

        $relationship = Resident::relationship;

        $rooms_ori = Location::where('location_description', 'like', 'Room _')->get();
        $rooms = [];
        foreach($rooms_ori as $room){
            $id = $room->location_master_id;
            $name = 'L'.$room->floor.' - '.$room->location_description;
            $rooms[$id] = $name;
        }

        if ($resident->image_url != null){
            $resident->image_url = Storage::disk('s3')->url($resident->image_url);
        }
        
        return view('residents.edit', compact('resident', 'tagsNull', 'current', 'available', 'relationship', 'rooms'),
                    ['success' => $resident->full_name.' created successfully.']);
        // return redirect()->route('residents.index')
        //     ->with('success', $resident->full_name.' updated successfully.');
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
        $tagsNull = Tag::doesntHave('resident')
            ->doesntHave('user')
            ->pluck('beacon_mac', 'beacon_id');
        
        $current = null;
        if(!empty($resident->tag)){
            $current = collect([$resident->tag->beacon_id => $resident->tag->beacon_mac]);
            $tagsNull = $current->concat($tagsNull);
        }

        $available = true;
        if($tagsNull->isEmpty()){
            $available = false;
        }

        $relationship = Resident::relationship;

        $rooms_ori = Location::where('location_description', 'like', 'Room _')->get();
        $rooms = [];
        foreach($rooms_ori as $room){
            $id = $room->location_master_id;
            $name = 'L'.$room->floor.' - '.$room->location_description;
            $rooms[$id] = $name;
        }

        if ($resident->image_url != null){
            $resident->image_url = Storage::disk('s3')->url($resident->image_url);
        }
        
        return view('residents.edit', compact('resident', 'tagsNull', 'current', 'available', 'relationship', 'rooms'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Tag  $resident
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateResidentRequest $request, Resident $resident)
    {
        $resident->update($request->all());

        if(!empty($resident->room)){
            $resident->room()->dissociate()->save();
        }

        $room = Location::find($request['location_room_id']);
        $resident->room()->associate($room)->save();

        /** Remove the tag associated with this user */
        if(!empty($resident->tag)){
            $resident->tag()->dissociate()->save();
        }

        if(!empty($request['beacon_id'])){
            $tag = Tag::find($request['beacon_id']);
            $tag->resident()->save($resident);
        }

        $image_id = "image-input";
 
        if ($request->hasFile($image_id)) {
            $extension = $request[$image_id]->extension();
            if ($request->file($image_id)->isValid()) {
                $validated = $request->validate([
                    'image-input' => 'mimes:jpeg,png|max:16384',
                ]);
                if ($resident->image_url != null){
                    $image_url = Storage::disk('s3')->delete($resident->image_url);
                    Storage::disk('s3-resized')->delete("resized-".$resident->image_url);
                }
                // return $image_url;
                $image_url = Storage::disk('s3')->putFile('residents', $request->file('image-input'));
                $resident->update(['image_url' => $image_url]);
            }
        }

        return Redirect::back()->with('success', $resident->full_name.' updated successfully.');
        // return redirect()->route('residents.index')
        //     ->with('success', $resident->full_name.' updated successfully');
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

    /**
     * Remove the specified resources from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function destroys(Request $request)
    {
        $ids = $request->residents_id;

        $residents = Resident::find($ids);

        foreach($residents as $resident){
            if(isset($resident->tag)){
                $resident->tag()->dissociate()->save();
            }
            Storage::disk('s3')->delete($resident->image_url);
            Storage::disk('s3-resized')->delete("resized-".$resident->image_url);
        }

        Resident::destroy($ids);

        if(count($ids) > 1){
            return response()->json([
                "success" => "Residents deleted successfully."
            ], 200);
        } else {
            return response()->json([
                "success" => "Resident deleted successfully."
            ], 200);
        }
    }
}
