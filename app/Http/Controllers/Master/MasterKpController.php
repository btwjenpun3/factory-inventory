<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MasterKpController extends Controller
{
    public function kpIndex()
    {
        return view('pages.master.kp.index');
    }
}
