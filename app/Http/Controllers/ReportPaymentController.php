<?php

namespace App\Http\Controllers;

use App\Helpers\CustomHelper;
use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\Approval;
use App\Models\Customer;
use Illuminate\Http\Request;
use Svg\Tag\Rect;

class ReportPaymentController extends Controller
{
    public function index()
    {
        $data = [
            'title'         => 'Laporan Pembayaran Proyek',
            'content'       => 'laporan_pembayaran',
        ];

        return view('layouts.index', ['data' => $data]);
    }
    public function process(Request $request){
        $data = [];
        return response()->json($data);
    }
}
