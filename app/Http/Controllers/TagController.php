<?php

namespace App\Http\Controllers;

use App\Resident;
use App\Tag;
use App\Policy;
use App\TagType;
use App\User;
use App\Scope;
use App\Services\TagTargetService;
use App\Rules\IsUniqueTarget;
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
        $tags = Tag::with('resident', 'user')->orderBy('beacon_id', 'asc')->get();

        return view('tags.index',compact('tags'));
    }
    
    /**
    * Show the form for creating a new resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function create(TagTargetService $tagTargetService)
    {
        $users = User::doesntHave('tag')->orderBy('fName', 'asc')->get();
        $residents = Resident::doesntHave('tag')->orderBy('resident_fName', 'asc')->get();
        
        $targetsNull = $tagTargetService->generateTagTarget($residents ,$users, NULL);
        $available = true;
        if($users->isEmpty() && $residents->isEmpty()){
            $available = false;
        }
        return view('tags.create',compact('users', 'residents', 'available', 'targetsNull'));
    }
    
    /**
    * Store a newly created resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    public function store(Request $request)
    {
        $rules = ['beacon_mac' => 'required|string|min:12|max:12|unique:beacons_table,beacon_mac,NULL,beacon_id,deleted_at,NULL'];
        $messages = [];

        if($request['assign'] == '1'){
            $rules['target'] = ['required', new IsUniqueTarget(null)];
            $messages['target.required'] = 'Please select at least one target.';
        }

        request()->validate($rules, $messages, ['beacon_mac' => 'mac address']);
        
        $tag = Tag::create($request->all());

        // /** Save the wristband tag type */
        // $tag_type = TagType::find(1);
        // $tag->tagType()->associate($tag_type)->save();

        if(!empty($request['target'])){
            $target_type = explode('-', $request['target'])[0];
            $target_id = explode('-', $request['target'])[1];

            if($target_type == "R"){
                $resident = Resident::find($target_id);
                $resident->tag()->associate($tag)->save();

                $policies = Policy::whereHas('scope', function($scope){
                    $scope->whereIn('target_type', ['A', 'R']);
                })->get();

            } else {
                $user = User::find($target_id);
                $user->tag()->associate($tag)->save();

                $policies = Policy::whereHas('scope', function($scope){
                    $scope->whereIn('target_type', ['A', 'U']);
                })->get();
            }

            foreach($policies as $policy){
                $policy->scope->tags()->attach($tag);
            }
        }
        
        return redirect()->route('beacons.index')
            ->with('success', $tag->beacon_mac.' added successfully.');
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
    public function edit(Tag $tag, TagTargetService $tagTargetService)
    {
        $users = User::doesntHave('tag')->get();
        $residents = Resident::doesntHave('tag')->get();

        /** Concat the current user of this tag, if exist */
        if(!empty($tag->user)){
            $current = $tag->user;
        } elseif (!empty($tag->resident)){
            $current = $tag->resident;
        } else {
            $current = null;
        }

        $targetsNull = $tagTargetService->generateTagTarget($residents ,$users, $current);

        $available = true;
        if($users->isEmpty() && $residents->isEmpty()){
            $available = false;
        }

        return view('tags.edit',compact('tag', 'users', 'current', 'residents', 'available', 'targetsNull'));
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
        $rules = ['beacon_mac' => 'required|string|min:12|max:12|unique:beacons_table,beacon_mac,'.$tag->beacon_id.',beacon_id,deleted_at,NULL'];
        $messages = [];

        if(isset($tag->user)){
            $current = $tag->user;
        } elseif (isset($tag->resident)){
            $current = $tag->resident;
        } else {
            $current = null;
        }

        if($request['assign'] == '1'){
            $rules['target'] = ['required', new IsUniqueTarget($current)];
            $messages['target.required'] = 'Please select at least one target.';
        }

        request()->validate($rules, $messages, ['beacon_mac' => 'mac address']);

        $tag->update($request->all());

        $pre_user_id = null;
        $pre_resident_id = null;
        /** Remove the user/resident associated with this tag */
        if(!empty($tag->user)){
            $pre_user_id = $tag->user->user_id;
            $tag->user->tag()->dissociate()->save();
        }

        if(!empty($tag->resident)){
            $pre_resident_id = $tag->resident->resident_id;
            $tag->resident->tag()->dissociate()->save();
        }

        // $scopes_custom = Scope::orWhereHas('tags', function($q) use($tag){
        //     $q->where('beacon_id', $tag->beacon_id);
        // })->get();

        
        // $scopes_custom = $scopes_custom->where('target_type', 'C')->get();
        
        $scopes = Scope::whereHas('policy', function($q){
            $q->where('deleted_at', null);
        })->get();

        $scopes_custom = $scopes->where('target_type', 'C');
        $scopes_custom_with_tag = collect();
        foreach($scopes_custom as $scope){
            return $scope->policy;
            if($scope->tags->contains('beacon_id', $tag->beacon_id)){
                $scopes_custom_with_tag->push($scope);
            }
        }
        
        foreach($scopes as $scope){
            $scope->tags()->detach($tag->beacon_id);
            $scope->policy->save();
        }

        if(!empty($request['target'])){
            $target_type = explode('-', $request['target'])[0];
            $target_id = explode('-', $request['target'])[1];

            if($target_type == "R"){
                $resident = Resident::find($target_id);
                $resident->tag()->associate($tag)->save();

                /* If the beacon owner didnt change, save them back into policy with custom target */
                if($resident->resident_id == $pre_resident_id){
                    foreach($scopes_custom_with_tag as $scope){
                        $scope->tags()->attach($tag->beacon_id);
                        $scope->policy->save();
                    }
                }

                $scopes_add = $scopes->whereIn('target_type', ['A', 'R']);

            } else {
                $user = User::find($target_id);
                $user->tag()->associate($tag)->save();

                /* If the beacon owner didnt change, save them back into policy with custom target */
                if($user->user_id == $pre_user_id){
                    foreach($scopes_custom_with_tag as $scope){
                        $scope->tags()->attach($tag->beacon_id);
                        $scope->policy->save();
                    }
                }

                $scopes_add = $scopes->whereIn('target_type', ['A', 'U']);
            }

            foreach($scopes_add as $scope){
                $scope->tags()->attach($tag);
                $scope->policy->save();
            }
        }

        foreach($scopes as $scope){
            $policy = $scope->policy;
            if(count($policy->all_targets) <= 0){
                $policy->alert_action = 0;
                $policy->save();
            }
        }

        return redirect()->route('beacons.index')
            ->with('success', $tag->beacon_mac.' updated successfully.');
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

        $tags = Tag::find($ids);
        
        foreach($tags as $tag){
            if(isset($tag->user)){
                $pre_tag_id = $tag->user->beacon_id;
                $tag->user->tag()->dissociate()->save();

                $scopes = Scope::whereHas('policy', function($q){
                    $q->where('deleted_at', null);
                })->whereNotIn('target_type', ['R'])->get();

                foreach($scopes as $scope){
                    if($scope->target_type == "C"){
                        if($scope->tags->contains('beacon_id', $pre_tag_id)){
                            $scope->tags()->detach($pre_tag_id);
                            $scope->policy->save();
                        }
                    } else {
                        $scope->tags()->detach($pre_tag_id);
                        $scope->policy->save();
                    }
                }
            } elseif (isset($tag->resident)){
                $pre_tag_id = $tag->resident->beacon_id;
                $tag->resident->tag()->dissociate()->save();

                $scopes = Scope::whereHas('policy', function($q){
                    $q->where('deleted_at', null);
                })->whereNotIn('target_type', ['U'])->get();

                foreach($scopes as $scope){
                    if($scope->target_type == "C"){
                        if($scope->tags->contains('beacon_id', $pre_tag_id)){
                            $scope->tags()->detach($pre_tag_id);
                            $scope->policy->save();
                        }
                    } else {
                        $scope->tags()->detach($pre_tag_id);
                        $scope->policy->save();
                    }
                }
            }
        }

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