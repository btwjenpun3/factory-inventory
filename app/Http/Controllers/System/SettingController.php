<?php

namespace App\Http\Controllers\System;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;

class SettingController extends Controller
{    
    public function keyId()
    {
        return '200';
    }    

    public function index() 
    {
        try {
            $keyValue = $this->keyId();
            $key = Setting::where('setting', 'activation_code')->firstOrFail();
            $keyValid = ($key->value == $keyValue);
        } catch (\Exception $e) {
            $keyValid = false;
        }    
        
        return view('pages.system.setting.index', [
            'key' => $keyValid
        ]);
    }
}
