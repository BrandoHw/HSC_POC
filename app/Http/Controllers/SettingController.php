<?php

namespace App\Http\Controllers;

use App\Tag;
use App\User;
use App\UserType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use DB;
use Session;

class SettingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        $users = User::orderBy('user_id', 'asc')->get();

        $tagsNull = Tag::doesntHave('resident')
            ->doesntHave('user')
            ->pluck('beacon_mac', 'beacon_id');
        
        $current = null;
        if(!empty($user->tag)){
            $current = collect([$user->tag->beacon_id => $user->tag->beacon_mac]);
            $tagsNull = $current->concat($tagsNull);
        }

        $available = true;
        if($tagsNull->isEmpty()){
            $available = false;
        }

        $roles = Role::orderBy('id','asc')->get();
        $rolePermissions = DB::table("role_has_permissions")
            ->get();

        return view('settings.index', compact('user', 'users', 'tagsNull',
            'roles', 'rolePermissions', 'current', 'available'));
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
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
