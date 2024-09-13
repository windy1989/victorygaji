<?php

namespace App\Http\Controllers;

use App\Helpers\CustomHelper;
use App\Http\Controllers\Controller;
use App\Models\Approval;
use Illuminate\Http\Request;

class ApprovalController extends Controller
{
    public function index()
    {
        $data = [
            'title'         => 'Persetujuan',
            'content'       => 'approval',
        ];

        return view('layouts.index', ['data' => $data]);
    }

    public function datatable(Request $request){
        $column = [
            'id',
            'created_at',
            'from_user_id',
            'approve_note',
            'approve_status',
            'approve_level',
            'approve_date',
        ];

        $start  = $request->start;
        $length = $request->length;
        $order  = $column[$request->input('order.0.column')];
        $dir    = $request->input('order.0.dir');
        $search = $request->input('search.value');

        $total_data = Approval::where('to_user_id',session('bo_id'))->whereNotNull('approve_status')->count();
        
        $query_data = Approval::where(function($query) use ($search, $request) {
                if($search) {
                    $query->where(function($query) use ($search, $request) {
                        $query->whereHas('fromUser',function($query) use ($search, $request) {
                            $query->where('nama','like',"%$search%");
                        });
                    });
                }
            })
            ->where('to_user_id',session('bo_id'))
            ->whereNotNull('approve_status')
            ->offset($start)
            ->limit($length)
            ->orderBy($order, $dir)
            ->get();

        $total_filtered = Approval::where(function($query) use ($search, $request) {
                if($search) {
                    $query->where(function($query) use ($search, $request) {
                        $query->whereHas('fromUser',function($query) use ($search, $request) {
                            $query->where('nama','like',"%$search%");
                        });
                    });
                }
            })
            ->where('to_user_id',session('bo_id'))
            ->whereNotNull('approve_status')
            ->count();

        $response['data'] = [];
        if($query_data <> FALSE) {
            $nomor = $start + 1;
            foreach($query_data as $val) {
				
                $response['data'][] = [
                    $nomor,
                    date('d/m/y H:i:s',strtotime($val->created_at)),
                    $val->fromUser->nama,
                    $val->approve_note,
                    $val->approveStatus(),
                    $val->approve_level,
                    $val->approve_date ? date('d/m/y H:i:s',strtotime($val->approve_date)) : '-',
                    $val->lookable->code,
                    '
                        <span style="font-size:25px;margin-left:10px;">
							<a href="#"><i class="flaticon-381-focus text-warning"></i></a>
                        </span>
					'
                ];

                $nomor++;
            }
        }

        $response['recordsTotal'] = 0;
        if($total_data <> FALSE) {
            $response['recordsTotal'] = $total_data;
        }

        $response['recordsFiltered'] = 0;
        if($total_filtered <> FALSE) {
            $response['recordsFiltered'] = $total_filtered;
        }

        return response()->json($response);
    }

    public function getCountApproval(Request $request){
        $count = Approval::where('to_user_id',session('bo_id'))->where('approve_status','1')->count();
        return response()->json($count);
    }
}
