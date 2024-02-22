<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Models\Rak;
use Illuminate\Support\Facades\Log;

class MasterAllocation extends Controller
{
    public function allocationIndex()
    {
        if(Gate::allows('has-activated') && Gate::allows('view-master-allocation')) {
            return view('pages.master.allocation.index');
        } else {
            return view('not-allowed');
        }  
    }

    public function allocationStore(Request $request)
    {
        try {
            $validation = $request->validate([
                'allocation' => 'required',
                'kind' => 'required'
            ]);
            if ($validation) {
                Rak::create([
                    'rak_name' => $request->allocation,
                    'jenis' => $request->kind,
                    'kode_jenis' => $this->allocationKindCode($request->kind)
                ]);
                return response()->json([
                    'success' => 'Master Allocation successfully saved'
                ], 200);
            } 
        } catch (\Exception $e) {
            Log::channel('master')->error('(Master Allocation) Theres an error : ' . $e->getMessage());
            return response()->json([
                'message' => 'Invalid Allocation or Kind. Please check again!'
            ], 400);
        }        
    }

    public function allocationShow(Request $request)
    {
        try {
            $rak = Rak::where('id_rak', $request->id)->first();
            return response()->json($rak);
        } catch (\Exception $e) {
            Log::channel('master')->error('(Master Buyer) Theres an error : ' . $e->getMessage());
            return response()->json([
                'message' => 'Theres an error. Please contanct administrator'
            ], 400);
        }
    }

    public function allocationUpdate(Request $request)
    {
        try {
            $validation = $request->validate([
                'allocation' => 'required',
                'kind' => 'required'
            ]);
            if($validation) {
                Rak::where('id_rak', $request->id)->update([
                    'rak_name' => $request->allocation,
                    'jenis' => $request->kind,
                    'kode_jenis' => $this->allocationKindCode($request->kind)
                ]);
                return response()->json([
                    'success' => 'Master Allocation successfully changed'
                ], 200);
            }
        } catch (\Exception $e) {
            Log::channel('master')->error('(Master Allocaiton) Theres an error : ' . $e->getMessage());
            return response()->json([
                'message' => 'Invalid Allocation or Kind. Please check again!'
            ], 400);
        }
    }

    public function allocationDelete(Request $request) 
    {
        try {
            Rak::where('id_rak', $request->id)->delete();
            return response()->json([
                'success' => 'Master Allocation successfully deleted'
            ], 200);
        } catch (\Exception $e) {
            Log::channel('master')->error('(Master Allocation) Theres an error : ' . $e->getMessage());
            return response()->json([
                'message' => 'Theres an error. Please contact administrator.'
            ], 400);
        }
    }

    private function allocationKindCode($kind)
    {
        if ($kind == 'Accessories') {
            $kindCode = 'A';
            return $kindCode;
        } else {
            $kindCode = 'B';
            return $kindCode;
        }
    }
}
