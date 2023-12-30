<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Supplier;

class PurchaseController extends Controller
{
    public function index() 
    {
        $suppliers = Supplier::get('supplier');
        return view('pages.purchase.index', [
            'suppliers' => $suppliers
        ]);
    }
}
