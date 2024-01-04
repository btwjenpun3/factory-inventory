<?php

namespace App\Http\Controllers\Chart;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kp;

class ChartController extends Controller
{
    public function kpChart()
    {
        $data = Kp::get();
        return response()->json($data);
    }
}
