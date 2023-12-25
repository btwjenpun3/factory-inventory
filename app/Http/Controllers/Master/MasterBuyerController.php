<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MasterBuyerController extends Controller
{
    public function buyerIndex()
    {
        return view('pages.master.buyer.index');
    }
}
