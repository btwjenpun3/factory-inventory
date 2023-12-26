<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{
    public function loginIndex()
    {
        return view('auth.login.index');
    }

    public function loginProcess(Request $request) 
    {
        $credentials = $request->validate([
            'email'     => ['required', 'email'],
            'password'  => ['required'],
        ]); 
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate(); 
            return redirect()->intended('/');
        } 
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function registerIndex()
    {
        return view('auth.register.index');
    }

    public function registerProcess(Request $request)
    {
        $validate = $request->validate([
            'name'      => 'required',
            'email'     => 'required|email',
            'password'  => 'required'
        ]);
        if($validate) 
        {
            User::create([
                'name'      => $request->name,
                'email'     => $request->email,
                'password'  => bcrypt($request->password),
                'role'      => 1,
                'status'    => 1
            ]);
            return redirect()->route('auth.login.index');
        }
    }

    public function logout(Request $request)
    {
        Auth::logout(); 
        $request->session()->invalidate(); 
        $request->session()->regenerateToken(); 
        return redirect()->route('auth.login.index');
    }
}
