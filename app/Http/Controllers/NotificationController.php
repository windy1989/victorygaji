<?php

namespace App\Http\Controllers;

use App\Helpers\CustomHelper;
use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\Approval;
use App\Models\Customer;
use Illuminate\Http\Request;
use Svg\Tag\Rect;

class NotificationController extends Controller
{
    public function index()
    {
        $data = [
            'title'         => 'Notifikasi',
            'content'       => 'notification',
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
                    <a href="'.env('APP_URL').'/persetujuan/detail/'.$val->code.'" class="btn btn-success btn-sm content-icon"><i class="fa fa-search"></i></a>
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

    public function getNotification(Request $request){
        $data = Activity::orderByDesc('id')->limit(10)->get()->sortBy('id');
        $notif = [];
        foreach($data as $row) {
            $notif[] = [
                'id'    => $row->id,
                'note'  => $row->note,
                'time'  => $row->getTimeAgo(),
            ];
        }
        return response()->json($notif);
    }
}
