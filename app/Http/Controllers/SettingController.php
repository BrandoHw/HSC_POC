<?php

namespace App\Http\Controllers;

use App\Role;
use App\Tag;
use App\User;
use App\UserType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Permission;
use DB;
use Illuminate\Support\Facades\Storage;
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

        $roles = Role::orderBy('id','asc')->with('color')->get();
        $rolePermissions = DB::table("role_has_permissions")
            ->get();
        $roles_count = count($roles);

        return view('settings.index', compact('user', 'users', 'tagsNull',
            'roles', 'rolePermissions', 'roles_count', 'current', 'available'));
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
