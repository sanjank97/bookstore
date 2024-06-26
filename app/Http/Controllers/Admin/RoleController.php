<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use DB;
class RoleController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:create-role|edit-role|delete-role', ['only' => ['index','show']]);
        $this->middleware('permission:create-role', ['only' => ['create','store']]);
        $this->middleware('permission:edit-role', ['only' => ['edit','update']]);
        $this->middleware('permission:delete-role', ['only' => ['destroy']]);
    }
 
    public function index()
    {
        $roles = Role::orderBy('id','DESC')->paginate(3);
        return view('admin.roles.index', [
            'roles' => $roles 
        ]);
    }

    public function create()
    {
       return view('admin.roles.create', [
        'permissions' => Permission::get()
       ]);
    }

    public function store(Request $request)
    {

        $validatedData = $request->validate([
            'name' => 'required|string|max:250|unique:roles,name',
            'permissions' => 'required',
         ]);

         $role = Role::create(['name' => $request->name]);
         $permissions = Permission::whereIn('id', $request->permissions)->get(['name'])->toArray();
         $role ->syncPermissions($permissions);

         return redirect()->route('roles.index')
         ->withSuccess('New role is added successfully.');
  
    }

    public function show(Role $role)
    {


        $rolePermissions = Permission::join("role_has_permissions","permission_id","=","id")
        ->where("role_id",$role->id)
        ->select('name')
        ->get();
        return view('admin.roles.show', [
            'role' => $role,
            'rolePermissions' => $rolePermissions
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Role $role)
    {
        
    
        if($role->name=='Super Admin'){
            abort(403, 'SUPER ADMIN ROLE CAN NOT BE EDITED');
        }

        $rolePermissions = DB::table("role_has_permissions")->where("role_id",$role->id)
            ->pluck('permission_id')
            ->all();

        return view('admin.roles.edit', [
            'role' => $role,
            'permissions' => Permission::get(),
            'rolePermissions' => $rolePermissions
        ]);  
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Role $role)
    {

        $validatedData = $request->validate([
            'name' => 'required|string|max:250|unique:roles,name,'.$role->id,
            'permissions' => 'required',
         ]);

        $input = $request->only('name');

        $role->update($input);

        $permissions = Permission::whereIn('id', $request->permissions)->get(['name'])->toArray();

        $role->syncPermissions($permissions);    
        
        return redirect()->route('roles.index')
                ->withSuccess('Role is updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role)
    {
       
        if($role->name=='Super Admin'){
            abort(403, 'SUPER ADMIN ROLE CAN NOT BE DELETED');
        }
        if(auth()->user()->hasRole($role->name)){
            abort(403, 'CAN NOT DELETE SELF ASSIGNED ROLE');
        }
        $role->delete();
        return redirect()->route('roles.index')
                ->withSuccess('Role is deleted successfully.');
    }
}
