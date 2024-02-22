<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Buyer;

class MasterBuyerController extends Controller
{
    public function buyerIndex()
    {
        $buyers = Buyer::all();
        return view('pages.master.buyer.index', [
            'buyers' => $buyers
        ]);
    }

    public function buyerStore(Request $request)
    {
        try {
            $validation = $request->validate([
                'code' => 'required',
                'name' => 'required'
            ]);
            if ($validation) {
                Buyer::create([
                    'code' => $request->code,
                    'name' => $request->name
                ]);
                return response()->json([
                    'success' => 'Master Buyer successfully saved'
                ], 200);
            } 
        } catch (\Exception $e) {
            Log::channel('master')->error('(Master Buyer) Theres an error : ' . $e->getMessage());
            return response()->json([
                'message' => 'Invalid Code or Name. Please check again!'
            ], 400);
        }        
    }

    public function buyerShow(Request $request)
    {
        try {
            $buyers = Buyer::where('id_buyer', $request->id)->first();
            return response()->json($buyers);
        } catch (\Exception $e) {
            Log::channel('master')->error('(Master Buyer) Theres an error : ' . $e->getMessage());
            return response()->json([
                'message' => 'Theres an error. Please contanct administrator'
            ], 400);
        }
    }

    public function buyerUpdate(Request $request)
    {
        try {
            $validation = $request->validate([
                'code' => 'required',
                'name' => 'required'
            ]);
            if($validation) {
                Buyer::where('id_buyer', $request->id)->update([
                    'name' => $request->name,
                    'code' => $request->code
                ]);
                return response()->json([
                    'success' => 'Master Buyer successfully changed'
                ], 200);
            }
        } catch (\Exception $e) {
            Log::channel('master')->error('(Master Buyer) Theres an error : ' . $e->getMessage());
            return response()->json([
                'message' => 'Invalid Code or Name. Please check again!'
            ], 400);
        }
    }

    public function buyerDelete(Request $request) 
    {
        try {
            Buyer::where('id_buyer', $request->id)->delete();
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
