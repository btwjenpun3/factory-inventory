<?php

namespace App\Http\Controllers\Warehouse;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;

class WarehouseController extends Controller
{
    public function receiveIndex()
    {
        if(Gate::allows('has-activated') && Gate::allows('view-warehouse-received')) {
            return view('pages.warehouse.received.index');
        } else {
            return view('not-allowed');
        }
    }

    public function requestIndex()
    {
        return view('pages.warehouse.request.index');
    }
}
