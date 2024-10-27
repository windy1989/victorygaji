<?php

namespace App\Http\Controllers;

use App\Helpers\CustomHelper;
use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\Approval;
use App\Models\Customer;
use App\Models\Project;
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
        $html = '';
        $html .= '<table class="table table-responsive-md mt-2">
                    <thead>
                        <tr>
                            <th><strong>#</strong></th>
                            <th><strong>No.Proyek</strong></th>
                            <th><strong>Customer</strong></th>
                            <th><strong>Nama Proyek</strong></th>
                            <th><strong>Tgl.Proyek</strong></th>
                            <th><strong>Status</strong></th>
                            <th><strong>Invoice</strong></th>
                            <th><strong>Nominal</strong></th>
                            <th><strong>Kwitansi</strong></th>
                        </tr>
                    </thead><tbody>';
        $data = Project::whereIn('status',['2','3'])->get();

        if($data->count() > 0){
            foreach($data as $key => $row){
                $rowspan = $row->invoice()->count();
                $html .= '<tr>
                    <td class="text-center" rowspan="'.$rowspan.'">'.($key + 1).'</td>
                    <td rowspan="'.$rowspan.'">'.$row->code.'</td>
                    <td rowspan="'.$rowspan.'">'.$row->customer->name.'</td>
                    <td rowspan="'.$rowspan.'">'.$row->name.'</td>
                    <td rowspan="'.$rowspan.'" class="text-center">'.date('d/m/Y',strtotime($row->post_date)).'</td>
                    <td rowspan="'.$rowspan.'" class="text-center">'.$row->statusBadge().'</td>
                ';

                if($rowspan == 0){
                    $html .= '<td></td>';
                    $html .= '<td></td>';
                    $html .= '<td></td>';
                }else{
                    foreach($row->invoice as $keypay => $payment){
                        if($keypay == 0){
                            $html .= '<td>'.$payment->code.'</td>';
                            $html .= '<td class="text-right">'.number_format($payment->nominal,2,',','.').'</td>';
                            $html .= '<td>'.($payment->receipt_code ?? '-').'</td>';
                        }
                    }
                }
                $html .= '</tr>';
                if($rowspan > 1){
                    foreach($row->invoice as $keypay => $payment){
                        if($keypay > 0){
                            $html .= '<tr>';
                            $html .= '<td>'.$payment->code.'</td>';
                            $html .= '<td class="text-right">'.number_format($payment->nominal,2,',','.').'</td>';
                            $html .= '<td>'.($payment->receipt_code ?? '-').'</td>';
                            $html .= '</tr>';
                        }
                    }
                }
            }
        }else{
            $html .= '<tr><td class="text-center" colspan="9">Data proyek tidak ditemukan.</td></tr>';
        }

        $html .= '</tbody></table>';

        return response()->json($html);
    }
}
