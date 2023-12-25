<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MasterSupplierController extends Controller
{
    public function supplierIndex()
    {
        return view('pages.master.supplier.index');
    }
}
