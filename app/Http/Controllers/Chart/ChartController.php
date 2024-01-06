<?php

namespace App\Http\Controllers\Chart;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kp;
use Illuminate\Support\Facades\DB;

class ChartController extends Controller
{
    public function kpChart()
    {
        $kp = Kp::select(DB::raw('etd, item, sum(qty) as total_qty'))
            ->whereNotNull('etd')
            ->where('etd', '<>', '0000-00-00')
            ->groupBy('etd', 'item')
            ->orderBy('etd', 'asc')
            ->get();

        return response()->json($kp);
    }
}
