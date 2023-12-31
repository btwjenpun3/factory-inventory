<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Supplier;
use Illuminate\Support\Facades\Gate;

class PurchaseController extends Controller
{
    public function index() 
    {
        $suppliers = Supplier::get('supplier');
        if(Gate::allows('has-activated') && Gate::allows('view-purchase')) {
            return view('pages.purchase.index', [
                'suppliers' => $suppliers
            ]);
        } else {
            return view('not-allowed');
        }        
    }
}
