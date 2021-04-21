<?php

namespace App\Http\Controllers;

use App\User;
use App\Services\PermissionService;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use DB;

class RoleController extends Controller
{
    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    function __construct()
    {
        $this->middleware('permission:role-list|role-create|role-edit|role-delete', ['only' => ['index','store']]);
        $this->middleware('permission:role-create', ['only' => ['create','store']]);
        $this->middleware('permission:role-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:role-delete', ['only' => ['destroy']]);
    }
    
    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function index(Request $request)
    {
        $roles = Role::orderBy('id','asc')->get();
        $rolePermissions = DB::table("role_has_permissions")
            ->get();
        $users = User::all();
        return view('roles.index',compact('roles', 'rolePermissions', 'users'));
    }

    /**
    * Show the form for creating a new resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function create(PermissionService $permissionService)
    {
        $permissions = Permission::get();
        $modules = $permissionService->organise_permissions($permissions);
        return view('settings.roles.create',compact('permissions', 'modules'));
    }
    
    /**
    * Store a newly created resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|unique:roles,name',
            'color' => 'required',
            'permission' => 'required',
        ]);
        $role = Role::create([
            'name' => $request['name'],
            'color' => $request['color']
        ]);
        $role->syncPermissions($request['permission']);
        return redirect()->route('roles.index')
            ->with('success','Role created successfully');
    }
    
    /**
    * Display the specified resource.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function show(PermissionService $permissionService, $id)
    {
        $role = Role::find($id);
        $rolePermissions = Permission::join("role_has_permissions","role_has_permissions.permission_id","=","permissions.id")
            ->where("role_has_permissions.role_id",$id)
            ->get();

        $modules = $permissionService->organise_permissions($rolePermissions);
        return response()->json([
            'role' => $role,
            'modules' => $modules],
            200);
    }
    
    /**
    * Show the form for editing the specified resource.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function edit(PermissionService $permissionService, $id)
    {
        $role = Role::find($id);
        $permissions = Permission::get();
        $rolePermissions = DB::table("role_has_permissions")
            ->where("role_has_permissions.role_id",$id)
            ->pluck('role_has_permissions.permission_id','role_has_permissions.permission_id')
            ->all();
        $modules = $permissionService->organise_permissions($permissions);
        $roleModules = $rolePermissions;
        return view('roles.edit',compact('role','modules','roleModules'));
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
        $this->validate($request, [
            'name' => 'required',
            'name' => 'required',
            'permission' => 'required',
        ]);
        $role = Role::find($id);

        $role->update([
                'name' => $request['name'],
                'color' => $request['color']
            ]);

        $role->syncPermissions($request->input('permission'));
        return redirect()->route('roles.index')
            ->with('success','Role updated successfully');
    }
    
    /**
    * Remove the specified resource from storage.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function destroy($id)
    {
        DB::table("roles")->where('id',$id)->delete();
        return response()->json([
            'success'=>'Role deleted.'],
            200);
    }

    
}