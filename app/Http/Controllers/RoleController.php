<?php

namespace App\Http\Controllers;

use App\Color;
use App\User;
use App\Role;
use App\Services\PermissionService;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
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
        $this->middleware('permission:role-list|role-create|role-edit|role-delete', ['only' => ['index','edit']]);
        $this->middleware('permission:role-create', ['only' => ['create','store']]);
        $this->middleware('permission:role-edit', ['only' => ['update']]);
        $this->middleware('permission:role-delete', ['only' => ['destroys']]);
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
    public function create(PermissionService $permissionService)
    {
        $permissions = Permission::get();
        $modules = $permissionService->organise_permissions($permissions);
        $colors_raw = Color::doesntHave('role')->orderBy('color_id', 'asc')->get();
        $colors = [];
        foreach($colors_raw as $color){
            $colors[$color->color_id] = $color->code_and_name;
        }
        return view('settings.roles.create',compact('permissions', 'modules', 'colors'));
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
            'name' => 'required|unique:roles,name,NULL,id,deleted_at,NULL',
            'color_id' => 'required|unique:roles,color_id,NULL,id,deleted_at,NULL',
            'permission' => 'required',
        ], [
            'color_id.required' => 'Please select the color.',
            'color_id.unique' => 'This color is taken. Please select another color.',
            'permission.required' => 'Please select at least one permission.'
        ]);
        $role = Role::create([
            'name' => $request['name'],
        ]);

        $color = Color::find($request['color_id']);
        $color->role()->save($role);

        $role->syncPermissions($request['permission']);
        return redirect()->route('settings.index')
            ->with('success', ucfirst($role->name).' created successfully');
    }
    
    /**
    * Display the specified resource.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function show(PermissionService $permissionService, $id)
    {
        //
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
        
        $colors_raw = Color::doesntHave('role')->orderBy('color_id', 'asc')->get();
        $colors = [];
        $colors[$role->color->color_id] = $role->color->code_and_name;
        foreach($colors_raw as $color){
            $colors[$color->color_id] = $color->code_and_name;
        }
        ksort($colors);

        return view('settings.roles.edit',compact('role','modules','roleModules', 'colors'));
    }
    
    /**
    * Update the specified resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  \App\Role  $role
    * @return \Illuminate\Http\Response
    */
    public function update(Request $request, Role $role)
    {
        $this->validate($request, [
            'name' => 'required|unique:roles,name,'.$role->id.',id,deleted_at,NULL',
            'color_id' => 'required|unique:roles,color_id,'.$role->id.',id,deleted_at,NULL',
            'permission' => 'required',
        ], [
            'color_id.required' => 'Please select the color.',
            'color_id.unique' => 'This color is taken. Please select another color.',
            'permission.required' => 'Please select at least one permission.'
        ]);

        $role->update([
            'name' => $request['name'],
        ]);

        $color = Color::find($request['color_id']);
        $color->role()->save($role);

        $role->syncPermissions($request->input('permission'));
        return redirect()->route('settings.index')
            ->with('success', ucfirst($role->name).' updated successfully');
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
        $ids = $request->roles_id;

        Role::destroy($ids);

        if(count($ids) > 1){
            return response()->json([
                "success" => "Roles deleted successfully."
            ], 200);
        } else {
            return response()->json([
                "success" => "Role deleted successfully."
            ], 200);
        }
    }

    
}