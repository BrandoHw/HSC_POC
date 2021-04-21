<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use App\User;
use App\UserType;
use App\Tag;
use Spatie\Permission\Models\Role;
use DB;
use Hash;

class UserController extends Controller
{
    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    function __construct()
    {
        $this->middleware('permission:user-list|user-view|user-create|user-edit|user-delete', ['only' => ['index','show']]);
        $this->middleware('permission:user-create', ['only' => ['create','store']]);
        $this->middleware('permission:user-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:user-delete', ['only' => ['destroy']]);
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
        $tagsNull = Tag::where('beacon_type', 2)
            ->doesntHave('user')
            ->pluck('beacon_mac', 'beacon_id');

        $userTypes = UserType::pluck('type', 'user_type_id')->all();
        return view('settings.users.create', compact('tagsNull', 'userTypes'));
    }
    
    /**
    * Store a newly created resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'fName' => 'required',
            'lName' => 'required',
            'phone_number' => 'required',
            'user_type' => 'required',
            'username' => 'required|unique:users,username',
            'password' => 'required
                |min:6
                |same:confirmPassword',
            'role_id' => 'required',
        ]);
        
        // if($validator->fails()){
        //     return response()->json([
        //         "errors" => $validator->errors()]);
        // }

        // $request['password'] = Hash::make($request['password']);
        // $user = User::create($request->all());
        // $user->assignRole([$request['role_id']]);
        // $userId = $user->id;
        // $userRole = $user->roles[0];
        // $userTagSerial = $user->tag->serial ?? "Not Assigned";
        // return response()->json([
        //     'success'=> '<strong>'.$user->name.'</strong> created.',
        //     "userId" => $userId,
        //     "userRole" => $userRole,
        //     "userTagSerial" => $userTagSerial],
        //     200);
    }
    
    /**
    * Show the form for editing the specified resource.
    *
    * @param  App\User  $user
    * @return \Illuminate\Http\Response
    */
    public function edit(User $user)
    {   
        $tagsNull = Tag::where('beacon_type', 2)
            ->doesntHave('user')
            ->pluck('beacon_mac', 'beacon_id');
        $userTypes = UserType::pluck('type', 'user_type_id')->all();
        return view('settings.users.edit', compact('user'));
    }
    
    /**
    * Display the specified resource.
    *
    * @param  App\User $user
    * @return \Illuminate\Http\Response
    */
    public function show(Request $request , User $user)
    {   
        // $userRole = $user->roles[0];
        // $userTag = $user->tag;
        // return response()->json([
        //     "user" => $user,
        //     "userRole" => $userRole,
        //     "userTag" => $userTag],
        //     200);
    }
    
    /**
    * Update the specified resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  App\User $user
    * @return \Illuminate\Http\Response
    */
    public function update(Request $request, User $user)
    {
        // $validator = Validator::make($request->all(), [
        //     'name' => 'required|unique:users,name,'.$user->id,
        //     'username' => 'required|unique:users,username,'.$user->id,
        //     'email' => 'required|email|unique:users,email,'.$user->id,
        //     'password' => 'same:confirmPassword',
        //     'role_id' => 'required',
        // ]);

        // if ($validator->fails()){
        //     return response()->json([
        //         "errors" => $validator->errors()]);
        // }

        // if(empty($request['password'])){
        //     $request = Arr::except($request,array('password'));
        // }
        // else{
        //     $passwordValidator = Validator::make($request->all(),['password' => 'min:6']);
            
        //     if($passwordValidator->fails())
        //     {
        //         // $validator->errors()->add('password', 'The password must be at least 6 characters');
        //         return response()->json([
        //             "errors" => $passwordValidator->errors()]);
        //     }
        //     else
        //     {
        //         $request['password'] = Hash::make($request['password']);
        //     }
        // }
        
        // $user->update($request->all());
        // DB::table('model_has_roles')->where('model_id',$user->id)->delete();
        // $user->assignRole([$request['role_id']]);
        // $userRole = $user->roles[0];
        // $userTag = $user->tag->serial ?? "Not Assigned";
        // return response()->json([
        //     'success'=> '<strong>'.$user->name.'</strong> updated.',
        //     "userRole" => $userRole,
        //     "userTag" => $userTag],
        //     200);
    }
        
    /**
    * Remove the specified resource from storage.
    *
    * @param  App\User $user
    * @return \Illuminate\Http\Response
    */
    public function destroy(User $user)
    {
        $name = $user->name;
        $user->delete();
        return response()->json([
            'success'=>'<strong>'.$name.'</strong> deleted.'],
            200);
    }
}

