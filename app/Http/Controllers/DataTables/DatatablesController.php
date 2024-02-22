<?php

namespace App\Http\Controllers\DataTables;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DataTables;
use App\Models\Item;
use App\Models\Buyer;
use App\Models\Kp;
use App\Models\KpTemporary;
use App\Models\Supplier;
use App\Models\User;
use App\Models\Role;
use App\Models\WarehouseRequest;
use App\Models\Rak;
use App\Models\KpTable;

class DatatablesController extends Controller
{  
    public function allocation()
    {
        $data = Rak::get();
        return DataTables::of($data)->toJson();
    }

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

    public function kpTemporary()
    {
        $data = KpTemporary::where('user_id', auth()->id())->get();
        return DataTables::of($data)->toJson();
    }

    public function approvalOrderPlan()
    {
        $data = Kp::orderBy('no', 'desc')->get();
        return DataTables::of($data)->toJson();
    }

    public function supplier()
    {
        $data = Supplier::get();
        return DataTables::of($data)->toJson();
    }

    public function merchandiserOrderPlan()
    {
        $data = Kp::get();
        return DataTables::of($data)->toJson();
    }

    public function purchasePurchasing()
    {
        $data = Kp::where('approve_order_plan', 1)->get();
        return DataTables::of($data)->toJson();
    }

    public function warehouseReceive()
    {
        $data = Kp::where('status', 1)->get();
        return DataTables::of($data)->toJson();
    }

    public function warehouseRequest()
    {
        $data = WarehouseRequest::get();
        return DataTables::of($data)->toJson();
    }

    public function users()
    {
        $data = User::with('role')->get();
        return DataTables::of($data)->toJson();
    }

    public function roles()
    {
        $data = Role::with('permissions')->get();
        return DataTables::of($data)->toJson();
    }

    public function certificates(Request $request)
    {
        $data = User::get();
        return DataTables::of($data)->toJson();
    }
}
