<?php

namespace App\Http\Controllers\Graphic;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class GraphicController extends Controller
{
    public function index()
    {
        if(Gate::allows('has-activated') && Gate::allows('view-graphic')) {
            return view('pages.graphic.index');
        } else {
            return view('not-allowed');
        }        
    }
}
