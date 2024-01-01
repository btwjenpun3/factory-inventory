<?php

namespace App\Http\Controllers\System;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    public function index()
    {
        $roles = Role::all();
        return view('pages.system.user.index', [
            'roles' => $roles
        ]);
    }

    public function create(Request $request) 
    {
        try {
            $validate = $request->validate([
                'name'      => 'required',
                'email'     => 'required|email|unique:web_user,email',
                'password'  => 'required|min:6',
                'role_id'   => 'required'
            ]);
            if($validate) {                
                    User::create([
                        'name'      => $request->name,
                        'email'     => $request->email,
                        'password'  => bcrypt($request->password),
                        'role_id'   => $request->role_id,
                        'status'    => 1
                    ]);
                    return response()->json([
                        'success' => 'New User created successfully'
                    ]);
                }                
        } catch(\Exception $e) {
            Log::error($e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }        
    }

    public function edit(Request $request) 
    {
        try {
            $user = User::where('id', $request->id)->first();
            return response()->json($user);
        } catch(\Exception $e) {
            Log::error($e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
        
    }

    public function update(Request $request)
    {
        try {
            $validate = $request->validate([
                'name'  => 'required',
                'email' => [
                    'required',
                    'email',
                    Rule::unique('web_user', 'email')->ignore($request->id)
                ],
                'role_id'  => 'required'
            ]);
            if($validate) {
                $data = [
                    'name'      => $request->name,
                    'email'     => $request->email,
                    'role_id'   => $request->role_id
                ];
                if($request->filled('password')){
                    $data['password'] = bcrypt($request->password);
                }
                User::where('id', $request->id)->update($data);
                return response()->json([
                    'success' => 'User update successfully'
                ], 200);
            }
        } catch(\Exception $e) {
            Log::error($e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getName(Request $request) 
    {
        try {
            $user = User::select('name')->where('id', $request->id)->firstOrFail();
            return response()->json($user);
        } catch(\Exception $e) {
            Log::error($e->getMessage());
        }
    }

    public function delete(Request $request) 
    {
        try {
            User::where('id', $request->id)->delete();
            return response()->json();
        } catch(\Exception $e) {
            Log::error($e->getMessage());
        }
    }
}
