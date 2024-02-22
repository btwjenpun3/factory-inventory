<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Supplier;
use App\Models\Kp;
use App\Models\KpTemporary;
use App\Models\Buyer;
use App\Models\OrderBuy;
use App\Models\Item;
use App\Models\KpTable;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class PurchaseController extends Controller
{
    public function indexOrderPlan() 
    {        
        if(Gate::allows('has-activated') && Gate::allows('view-merchandiser-order-plan')) {
            $suppliers = Supplier::get('supplier');
            $buyers = Buyer::get();
            $orderBuys = OrderBuy::get();
            $items = Item::get();
            return view('pages.merchandiser.order-plan.index', [
                'suppliers' => $suppliers,
                'buyers' => $buyers,
                'orderBuys' => $orderBuys,
                'items' => $items
            ]);
        } else {
            return view('not-allowed');
        }        
    }

    public function indexListKp() 
    {        
        if(Gate::allows('has-activated') && Gate::allows('view-merchandiser-production-card')) {      
            $suppliers = Supplier::get('supplier');      
            return view('pages.merchandiser.list-kp.index', [
                'suppliers' => $suppliers
            ]);
        } else {
            return view('not-allowed');
        }        
    }

    public function showDetail(Request $request)
    {
        $data = Kp::where('no', $request->id)->firstOrFail();
        return response()->json($data);
    }

    public function showTemporary(Request $request)
    {
        $data = KpTemporary::where('id', $request->id)->firstOrFail();
        return response()->json($data);
    }

    public function store(Request $request)
    {
        try {
            $validation = $request->validate([
                'buyer' => 'required',
                'po_buyer' => 'required',
                'item' => 'required',
                'desc' => 'required',
                'size' => 'required',
                'uom' => 'required',
                'quantity' => 'required|integer',
                'uom2' => 'required',
                'color' => 'required',
                'po_supplier' => 'required',
                'qty_garment' => 'required|integer',
            ]);
            if($validation){
                KpTemporary::create([
                    'user_id' => auth()->id(),
                    'code_buyer' => $request->buyer,
                    'po_buyer' => $request->po_buyer,
                    'item' => $request->item,
                    'item_description' => $request->desc,
                    'size' => $request->size,
                    'unit_of_material' => $request->uom,
                    'quantity' => $request->quantity,
                    'unit_of_material_2' => $request->uom2,
                    'color' => $request->color,
                    'po_supplier' => $request->po_supplier,
                    'quantity_garment' => $request->qty_garment
                ]);
                return response()->json([
                    'success' => 'Order Plan successfully created'
                ], 200);
            }
        } catch (\Exception $e) {
            Log::channel('order-plan')->error('(Order Plan) Theres an error : ' . $e->getMessage());
            return response()->json([
                'message' => 'Theres an error. Please contact administrator.'
            ], 400);
        }
    }

    public function getItem(Request $request)
    {
        try {
            $data = Item::where('code_buyer', $request->code_buyer)->first();
            return response()->json($data);
        } catch (\Exception $e) {
            Log::channel('order-plan')->error('(Order Plan) Theres an error : ' . $e->getMessage());
            return response()->json([
                'message' => 'Theres an error. Please contact administrator.'
            ], 400);
        }
    }

    public function getQuantityGarment(Request $request)
    {
        try {
            $data = OrderBuy::where('po_buyer', $request->po_buyer)->first();
            return response()->json($data);
        } catch (\Exception $e) {
            Log::channel('order-plan')->error('(Order Plan) Theres an error : ' . $e->getMessage());
            return response()->json([
                'message' => 'Theres an error. Please contact administrator.'
            ], 400);
        }
    }

    public function approveAll(Request $request)
    {
        try {
            $data = $request->data;
            $arrayLength = count($data) - 1;
            $kpLast = Kp::orderBy('no', 'desc')->first();
            $kpNumberPrefix = $data[0]['code_buyer'];
            $kpNumberAffix = $kpLast ? sprintf('%07d', $kpLast->no + 1) : '0000001';
            $kpNumber = $kpNumberPrefix . $kpNumberAffix;
            for($i = 0; $i <= $arrayLength; $i++) {
                $itemName = Item::where('code_buyer', $data[$i]['item'])->first();
                $saveOrderPlan = Kp::create([
                    'kp' => $kpNumber,
                    'item' => $itemName->items,
                    'color' => $data[$i]['color'],
                    'po_buyer' => $data[$i]['po_buyer'],
                    'desc' => $data[$i]['item_description'],
                    'po_sup' => $data[$i]['po_supplier'],
                    'qty' => $data[$i]['quantity'],
                    'qty_gar' => $data[$i]['quantity_garment'],
                    'size' => $data[$i]['size'],
                    'uom' => $data[$i]['unit_of_material'],
                    'uom1' => $data[$i]['unit_of_material_2'],
                    'create_date' => Carbon::now()
                ]);
                if($saveOrderPlan) {
                    KpTemporary::where('id', $data[$i]['id'])->delete();                    
                }
            }
            return response()->json([
                'success' => 'Order Plan send for approve with number ' . $kpNumber
            ], 200);            
        } catch (\Exception $e) {
            Log::channel('order-plan')->error('(Order Plan) Theres an error : ' . $e->getMessage());
            return response()->json([
                'message' => 'Theres an error. Please contact administrator.'
            ], 400);
        }
    }

    public function destroy(Request $request)
    {
        try {
            KpTemporary::where('id', $request->id)->delete();
            return response()->json([
                'success' => 'Order Plan successfully deleted'
            ], 200); 
        } catch (\Exception $e) {
            Log::channel('order-plan')->error('(Order Plan) Theres an error : ' . $e->getMessage());
            return response()->json([
                'message' => 'Theres an error. Please contact administrator.'
            ], 400);
        }
    }
}
