<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Supplier;

class MasterSupplierController extends Controller
{
    public function supplierIndex()
    {
        return view('pages.master.supplier.index');
    }

    public function supplierStore(Request $request)
    {
        try {
            $validation = $request->validate([
                'id' => 'required',
                'supplier' => 'required',
                'address' => 'required'
            ]);
            if ($validation) {
                Supplier::create([
                    'id' => $request->id,
                    'supplier' => $request->supplier,
                    'address' => $request->address
                ]);
                return response()->json([
                    'success' => 'Master Supplier successfully saved'
                ], 200);
            } 
        } catch (\Exception $e) {
            Log::channel('master')->error('(Master Supplier) Theres an error : ' . $e->getMessage());
            return response()->json([
                'message' => 'Invalid Code or Name. Please check again!'
            ], 400);
        }  
    }

    public function supplierShow(Request $request)
    {
        try {
            $suppliers = Supplier::where('id_supplier', $request->id)->first();
            return response()->json($suppliers);
        } catch (\Exception $e) {
            Log::channel('master')->error('(Master Buyer) Theres an error : ' . $e->getMessage());
            return response()->json([
                'message' => 'Theres an error. Please contanct administrator'
            ], 400);
        }
    }

    public function supplierUpdate(Request $request)
    {
        try {
            $validation = $request->validate([
                'id' => 'required',
                'supplier' => 'required',
                'address' => 'required',
            ]);
            if($validation) {
                Supplier::where('id_supplier', $request->idSupplier)->update([
                    'id' => $request->id,
                    'supplier' => $request->supplier,
                    'address' => $request->address
                ]);
                return response()->json([
                    'success' => 'Master Supplier successfully changed'
                ], 200);
            }
        } catch (\Exception $e) {
            Log::channel('master')->error('(Master Supplier) Theres an error : ' . $e->getMessage());
            return response()->json([
                'message' => 'Invalid.' . $e->getMessage()
            ], 400);
        }
    }

    public function supplierDelete(Request $request) 
    {
        try {
            Supplier::where('id_supplier', $request->id)->delete();
            return response()->json([
                'success' => 'Master Buyer successfully deleted'
            ], 200);
        } catch (\Exception $e) {
            Log::channel('master')->error('(Master Buyer) Theres an error : ' . $e->getMessage());
            return response()->json([
                'message' => 'Theres an error. Please contact administrator.'
            ], 400);
        }
    }
}
