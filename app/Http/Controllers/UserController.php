<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use App\Http\Requests\AddUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Role;
use App\Scope;
use App\User;
use App\UserType;
use App\Tag;
use DB;
use Hash;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    function __construct()
    {
        $this->middleware('permission:user-list|user-view|user-create|user-edit|user-delete', ['only' => ['index','edit']]);
        $this->middleware('permission:user-create', ['only' => ['create','store']]);
        $this->middleware('permission:user-edit', ['only' => ['update', 'reset_password']]);
        $this->middleware('permission:user-delete', ['only' => ['destroy', 'destroys']]);
    }

    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function index(Request $request)
    {
        //
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
            ->pluck('beacon_mac', 'beacon_id')
            ->all();
        
        $roles = Role::orderBy('id','asc')->get();
        $rolePermissions = DB::table("role_has_permissions")
            ->get();
        
        $available = true;
        if(count($tagsNull) < 1){
            $available = false;
        }

        return view('settings.users.create', compact('tagsNull', 'roles', 'available'));
    }
    
    /**
    * Store a newly created resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    public function store(AddUserRequest $request)
    {
        $request['password'] = Hash::make($request['username'].'@123');
        $user = User::create($request->all());

        if(!empty($request['role'])){
            DB::table('model_has_roles')->where('model_id',$user->user_id)->delete();
            $user->assignRole([$request['role']]);
        }

        if(!empty($request['beacon_id'])){
            $tag = Tag::find((int)$request['beacon_id']);
            $tag->user()->save($user);

            $scopes = Scope::whereHas('policy', function($q){
                $q->where('deleted_at', null);
            })->whereNotIn('target_type', ['R', 'C'])->get();

            foreach($scopes as $scope){
                $scope->tags()->attach($tag);
                $scope->policy->save();
            }
        }

        $request->session()->flash('user', true);
        $request->session()->flash('success', $user->full_name.' created successfully.');
        return redirect()->route('settings.index');
    }
    
    /**
    * Show the form for editing the specified resource.
    *
    * @param  App\User  $user
    * @return \Illuminate\Http\Response
    */
    public function edit(User $user)
    {   
        $tagsNull = Tag::doesntHave('resident')
            ->doesntHave('user')
            ->pluck('beacon_mac', 'beacon_id')
            ->all();
        
        $current = null;
        if(!empty($user->tag)){
            $current = collect([$user->tag->beacon_id => $user->tag->beacon_mac]);
            $tagsNull = Arr::prepend($tagsNull, $user->tag->beacon_mac, $user->tag->beacon_id);
        }

        $available = true;
        if(count($tagsNull) < 1){
            $available = false;
        }

        $roles = Role::orderBy('id','asc')->get();
        $rolePermissions = DB::table("role_has_permissions")
            ->get();
        return view('settings.users.edit', compact('user', 'tagsNull', 'roles', 'current', 'available'));
    }
    
    /**
    * Display the specified resource.
    *
    * @param  App\User $user
    * @return \Illuminate\Http\Response
    */
    public function show(Request $request , User $user)
    {   
        //
    }
    
    /**
    * Update the specified resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  App\User $user
    * @return \Illuminate\Http\Response
    */
    public function update(UpdateUserRequest $request, User $user)
    {
        if(!empty($user->tag)){
            $pre_tag_id = $user->tag->beacon_id;
        }
        $user->update($request->all());

        if(!empty($request['role'])){
            DB::table('model_has_roles')->where('model_id',$user->user_id)->delete();
            $user->assignRole([$request['role']]);
        }

        /** Remove the tag associated with this user then save new tag (if exist) to user and scopes associated with this user*/
        if(!empty($user->tag)){
            $pre_tag_id = $user->tag->beacon_id;
            $user->tag()->dissociate()->save();

            $scopes = Scope::whereHas('policy', function($q){
                $q->where('deleted_at', null);
            })->whereNotIn('target_type', ['R'])->get();

            if(!empty($request['beacon_id'])){
                $tag = Tag::find((int)$request['beacon_id']);
                $tag->user()->save($user);

                foreach($scopes as $scope){
                    if($scope->target_type == "C"){
                        if($scope->tags->contains('beacon_id', $pre_tag_id)){
                            $scope->tags()->detach($pre_tag_id);
                            $scope->tags()->attach($tag->beacon_id);
                            $scope->policy->save();
                        }
                    } else {
                        $scope->tags()->detach($pre_tag_id);
                        $scope->tags()->attach($tag->beacon_id);
                        $scope->policy->save();
                    }
                }
                
            } else {
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
        } else {
            if(!empty($request['beacon_id'])){
                $tag = Tag::find((int)$request['beacon_id']);
                $tag->user()->save($user);

                $scopes = Scope::whereHas('policy', function($q){
                    $q->where('deleted_at', null);
                })->whereIn('target_type', ['A', 'U'])->get();
    
                foreach($scopes as $scope){
                    $scope->tags()->attach($tag->beacon_id);
                    $scope->policy->save();
                }
            }
        }

        $request->session()->flash('user', true);
        $request->session()->flash('success',  $user->full_name.' updated successfully.');
        return redirect()->route('settings.index');
    }

    /**
    * Update the specified resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    public function change_profile(UpdateUserRequest $request)
    {
        $user = User::find($request['user_id']);
        $user->update($request->all());
        $permissions = $user->getPermissionsViaRoles()->pluck('name')->all();

        $image_id = "image-input";
        if ($request->hasFile($image_id)) {
            $extension = $request[$image_id]->extension();
            $filename = "user-".$user->user_id.".".$extension;
            if ($request->file($image_id)->isValid()) {
                $validated = $request->validate([
                    'image-input' => 'mimes:jpeg,png|max:16384',
                ]);
                $image_url = Storage::disk('s3')->putFileAs(
                'users', $request->file('image-input'), $filename,
                );
                $user->update(['image_url' => $image_url]);
            }
        }

        if(in_array('user-edit', $permissions) || in_array('beacon-edit', $permissions)){
            if(!empty($request['role'])){
                DB::table('model_has_roles')->where('model_id',$user->user_id)->delete();
                $user->assignRole([$request['role']]);
            }
    
            /** Remove the tag associated with this user */
            if(!empty($user->tag)){
                $user->tag()->dissociate()->save();
            }
    
            if(!empty($request['beacon_id'])){
                $tag = Tag::find($request['beacon_id']);
                $tag->user()->save($user);
            }
        }

        $request->session()->flash('personal', true);
        $request->session()->flash('success', 'Profile updated successfully.');
        return redirect()->route('settings.index');
    }

    /**
    * Update the specified resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    public function change_password(Request $request)
    {
        $user = Auth::user();
        $request->session()->flash('password', true);

        request()->validate([
            'old_password' => 'required',
            'new_password' => 'required|confirmed|min:8|different:old_password',
            'new_password_confirmation' => 'required',
        ], [
            'new_password_confirmation.required' => 'Please eneter new password again in the input field.'
        ]);
        
        $password = $request['old_password'];
        $new_password = $request['new_password'];

        if(Hash::check($password, $user->password)){
            $user->forceFill(['password' => Hash::make($new_password)]);
            $user->save();
            $request->session()->flash('success', 'Password updated successfully.');
        } else {
            $request->session()->flash('error', 'Password does not match.');
        }
        return redirect()->route('settings.index');
    }

    /**
    * Reset the specified resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    public function reset_password(Request $request)
    {
        $user = User::find($request['user_id']);

        $reset_password = $user->username.'@123';

        $user->forceFill(['password' => Hash::make($reset_password)]);
        $user->save();

        return response()->json([
            "success" => "Password reset successfully."
        ], 200);
    }

    /**
    * Remove the specified resource from storage.
    *
    * @param  App\User $user
    * @return \Illuminate\Http\Response
    */
    public function destroy(User $user)
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
        $ids = $request->users_id;

        $users = User::find($ids);

        foreach($users as $user){
            if(isset($user->tag)){
                $pre_tag_id = $user->tag->beacon_id;
                $user->tag()->dissociate()->save();

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
            }
        }
        
        User::destroy($ids);

        if(count($ids) > 1){
            return response()->json([
                "success" => "Users deleted successfully."
            ], 200);
        } else {
            return response()->json([
                "success" => "User deleted successfully."
            ], 200);
        }
    }
}

