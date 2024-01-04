<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Supplier;
use App\Models\Kp;
use Illuminate\Support\Facades\Gate;

class PurchaseController extends Controller
{
    public function index() 
    {        
        if(Gate::allows('has-activated') && Gate::allows('view-purchase')) {
            $suppliers = Supplier::get('supplier');
            return view('pages.purchase.index', [
                'suppliers' => $suppliers
            ]);
        } else {
            return view('not-allowed');
        }        
    }

    public function showDetail(Request $request)
    {
        $data = Kp::where('no', $request->id)->firstOrFail();
        return response()->json($data);
    }
}
