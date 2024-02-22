<?php

namespace App\Http\Controllers\Purchase;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Kp;
use Illuminate\Support\Facades\Gate;

class PurchasingController extends Controller
{
    public function index()
    {
        if(Gate::allows('has-activated') && Gate::allows('view-purchase-purchasing')) {
            return view('pages.purchase.purchasing.index');
        } else {
            return view('not-allowed');
        }
    }

    public function show(Request $request)
    {
        try {
            $data = Kp::where('no', $request->id)->first();
            return response()->json($data);
        } catch (\Exception $e) {
            Log::channel('order-plan')->error('(Purchase Purchasing) Theres an error : ' . $e->getMessage());
            return response()->json([
                'message' => 'Theres an error. Please contact administrator.'
            ], 400);
        }
    }

    public function update(Request $request)
    {
        try {
            $data = Kp::where('no', $request->id)->first();
            $data->update([
                'supp' => $request->supplier,
                'no_invo' => $request->no_invoice,
                'idr' => $request->currency,
                'price' => $request->price,
                'etd' => $request->etd,
                'awb' => $request->awb
            ]);
            return response()->json([
                'success' => 'Purchase for ' . $data->kp . ' successfully updated'
            ], 200);
        } catch (\Exception $e) {
            Log::channel('order-plan')->error('(Purchase Purchasing) Theres an error : ' . $e->getMessage());
            return response()->json([
                'message' => 'Theres an error. Please contact administrator.'
            ], 400);
        }
    }

    public function approve(Request $request)
    {
        try {
            $data = Kp::where('no', $request->id)->first();
            $data->update([
                'status' => 1,
            ]);
            return response()->json([
                'success' => 'Purchase for ' . $data->kp . ' successfully approved'
            ], 200);
        } catch (\Exception $e) {
            Log::channel('order-plan')->error('(Purchase Purchasing) Theres an error : ' . $e->getMessage());
            return response()->json([
                'message' => 'Theres an error. Please contact administrator.'
            ], 400);
        }
    }
}
