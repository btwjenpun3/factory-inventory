<?php

namespace App\Http\Controllers\Export;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Exports\PurchaseExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Gate;

class ExportController extends Controller
{
    public function index()
    {
        if(Gate::allows('has-activated') && Gate::allows('view-export')) {
            return view('pages.export.index');
        } else {
            return view('not-allowed');
        }
        
    }

    public function exportToExcel(Request $request)
    {
        if(Gate::allows('has-activated') && Gate::allows('view-export')) {
            if($request->export == 'purchase') {
                return Excel::download(new PurchaseExport, 'kp.xlsx');
            } elseif ($request->export == 'empty') {
                return redirect()->back()->with('error', 'Please select Data!');
            } else {
                return redirect()->back()->with('error', 'Data not found!');
            }
        } else {
            return view('not-allowed');
        }       
    }
}
