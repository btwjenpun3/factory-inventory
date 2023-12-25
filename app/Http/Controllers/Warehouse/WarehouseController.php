<?php

namespace App\Http\Controllers\Warehouse;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class WarehouseController extends Controller
{
    public function receiveIndex()
    {
        return view('pages.warehouse.receive.index');
    }

    public function requestIndex()
    {
        return view('pages.warehouse.request.index');
    }
}
