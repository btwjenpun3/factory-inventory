<?php

namespace App\Http\Controllers\System;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Support\Facades\Log;

class RoleController extends Controller
{
    public function index()
    {
        $permissions = Permission::get();      
        return view('pages.system.role.index', [
            'permissions' => $permissions
        ]);
    }

    public function create(Request $request)
    {
        try {
            $validation = $request->validate([
                'role' => 'required|unique:web_roles,name'
            ]);
            if($validation) {
                Role::create([
                    'name' => $request->role
                ]);
                return response()->json([
                    'success' => 'New Role successfully added'
                ], 200);
            }
        } catch(\Exception $e) {
            Log::error($e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function edit(Request $request)
    {
        $role = Role::where('id', $request->id)->firstOrFail();
        $data = Role::with('permissions')->find($request->id);
        return response()->json([
            'permissions' => $data->permissions,
            'role' => $role
        ]);
    }

    public function update(Request $request)
    {
        $role = Role::find($request->id);
        $role->update([
            'name' => $request->name
        ]);
        $role->permissions()->sync($request->input('permissions', []));    
        return response()->json(['success' => 'Role updated successfully']);
    }
    
    public function getRole(Request $request)
    {
        try {
            $role = Role::select('name')->where('id', $request->id)->firstOrFail();
            return response()->json($role);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }
    }

    public function delete(Request $request)
    {
        try {
            Role::where('id', $request->id)->delete();
            return response()->json([
                'success' => 'Role successfully deleted'
            ]);
        } catch(\Exception $e) {
            Log::error($e->getMessage());
        }
    }
}
