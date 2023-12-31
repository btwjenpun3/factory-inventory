<?php

namespace App\Http\Controllers\System;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Role;
use App\Models\Permission;

class RoleController extends Controller
{
    public function index()
    {
        $permissions = Permission::get();      
        return view('pages.system.role.index', [
            'permissions' => $permissions
        ]);
    }

    public function edit(Request $request)
    {
        $data = Role::with('permissions')->find($request->id);
        return response()->json(['permissions' => $data->permissions]);
    }

    public function update(Request $request)
    {
        $role = Role::find($request->id);
        $role->permissions()->sync($request->input('permissions', []));    
        return response()->json(['success' => 'Role updated successfully']);
    }
    
}
