<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Buyer;
use App\Models\Item;
use App\Models\Kp;
use Illuminate\Support\Facades\Log;

class MasterItemController extends Controller
{
    public function itemIndex() 
    {
        $buyers = Buyer::get();
        return view('pages.master.item.index', [
            'buyers' => $buyers
        ]);;
    }   

    public function itemStore(Request $request)
    { 
        try {
            $validate = $request->validate([
                'code_buyer'    => 'required',
                'items'         => 'required',
                'desc'          => 'required'
            ]);
            if ($validate) {
                Item::create([
                    'code_buyer'    => $request->code_buyer,
                    'items'         => $request->items,
                    'desc'          => $request->desc
                ]);
                return response()->json([
                    'success' => 'Data successfully created'
                ]);
            }
        } catch (\Exception $e) {
            Log::channel('master')->error('(Master Item) Theres an error : ' . $e->getMessage());
            return response()->json([
                'message' => 'Invalid Code Buyer, Items, or Desc. Please check again!'
            ], 400);
        }       
    }

    public function itemShow(Request $request)
    {
        $data = Item::where('id_item', $request->id)->first();
        return response()->json($data);
    }

    public function itemUpdate(Request $request)
    {   
        $validate = $request->validate([
            'code_buyer'    => 'required',            
            'items'         => 'required',
            'desc'          => 'required'
        ]);
        if ($validate) {
            $item = Item::where('id_item', $request->id)->first();            
            $item->update([
                'code_buyer'    => $request->code_buyer,                
                'items'         => $request->items,
                'desc'          => $request->desc
            ]); 
            return response()->json(['success' => 'Data successfully update'], 200);      
        }
    }

    public function itemDelete(Request $request)
    {
        try {
            Item::where('id_item', $request->id)->delete();
            return response()->json(['success' => 'Data successfully deleted'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to delete data'], 500);
        }
    }
}
