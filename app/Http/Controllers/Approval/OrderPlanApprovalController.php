<?php

namespace App\Http\Controllers\Approval;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kp;
use Illuminate\Support\Facades\Log;

class OrderPlanApprovalController extends Controller
{
    public function index()
    {
        return view('pages.approval.order-plan.index');
    }

    public function approve(Request $request) {
        try {
            $data = Kp::where('no', $request->id)->first();
            if ($data) {
                $data->update([
                    'approve_order_plan' => 1
                ]);
                return response()->json([
                    'success' => 'Order Plan ' . $data->kp . ' approved'
                ], 200);
            } else {
                return response()->json([
                    'message' => 'Order plan not found'
                ], 400);
            }            
        } catch (\Exception $e) {
            Log::channel('order-plan')->error('(Approved Order Plan) Theres an error : ' . $e->getMessage());
            return response()->json([
                'message' => 'Theres an error. Please contact administrator.'
            ], 400);
        }
    }

    public function reject(Request $request) {
        try {
            $data = Kp::where('no', $request->id)->first();
            if ($data) {
                $data->update([
                    'approve_order_plan' => 2
                ]);
                return response()->json([
                    'success' => 'Order Plan ' . $data->kp . ' rejected!'
                ], 200);
            } else {
                return response()->json([
                    'message' => 'Order plan not found'
                ], 400);
            }            
        } catch (\Exception $e) {
            Log::channel('order-plan')->error('(Approved Order Plan) Theres an error : ' . $e->getMessage());
            return response()->json([
                'message' => 'Theres an error. Please contact administrator.'
            ], 400);
        }
    }
}
