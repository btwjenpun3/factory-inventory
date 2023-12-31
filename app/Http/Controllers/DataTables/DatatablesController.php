<?php

namespace App\Http\Controllers\DataTables;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DataTables;
use App\Models\Item;
use App\Models\Buyer;
use App\Models\Kp;
use App\Models\Supplier;
use App\Models\User;
use App\Models\WarehouseRequest;

class DatatablesController extends Controller
{  
    public function item()
    {
        $data = Item::get();
        return DataTables::of($data)->toJson();
    }

    public function buyer()
    {
        $data = Buyer::get();
        return DataTables::of($data)->toJson();
    }

    public function kp()
    {
        $data = Kp::get();
        return DataTables::of($data)->toJson();
    }

    public function supplier()
    {
        $data = Supplier::get();
        return DataTables::of($data)->toJson();
    }

    public function purchase()
    {
        $data = Kp::get();
        return DataTables::of($data)->toJson();
    }

    public function warehouseReceive()
    {
        $data = Kp::get();
        return DataTables::of($data)->toJson();
    }

    public function warehouseRequest()
    {
        $data = WarehouseRequest::get();
        return DataTables::of($data)->toJson();
    }

    public function users()
    {
        $data = User::get();
        return DataTables::of($data)->toJson();
    }
}
