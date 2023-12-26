<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Buyer;
use App\Models\Item;

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
        $validate = $request->validate([
            'code_buyer'    => 'required',
            'items'         => 'required',
            'desc'          => 'required'
        ]);
        if($validate) 
        {
            Item::create([
                'code_buyer'    => $request->code_buyer,
                'items'         => $request->items,
                'desc'          => $request->desc
            ]);
            return response()->json([
                'success' => 'Data successfully created'
            ]);
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
        if($validate) 
        {
            Item::where('id_item', $request->id)->update([
                'code_buyer'    => $request->code_buyer,
                'items'         => $request->items,
                'desc'          => $request->desc
            ]);
            return response()->json([
                'success' => 'Data successfully updated'
            ]);
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
