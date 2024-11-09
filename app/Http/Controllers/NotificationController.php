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
            'user_id',
            'title',
            'note',
            'created_at',
        ];

        $start  = $request->start;
        $length = $request->length;
        $order  = $column[$request->input('order.0.column')];
        $dir    = $request->input('order.0.dir');
        $search = $request->input('search.value');

        $total_data = Activity::count();
        
        $query_data = Activity::where(function($query) use ($search, $request) {
                if($search) {
                    $query->where(function($query) use ($search, $request) {
                        $query->where('title','like',"%$search%")
                            ->orWhere('note','like',"%$search%");
                    });
                }
            })
            ->offset($start)
            ->limit($length)
            ->orderBy($order, $dir)
            ->get();

        $total_filtered = Activity::where(function($query) use ($search, $request) {
                if($search) {
                    $query->where(function($query) use ($search, $request) {
                        $query->where('title','like',"%$search%")
                            ->orWhere('note','like',"%$search%");
                    });
                }
            })
            ->count();

        $response['data'] = [];
        if($query_data <> FALSE) {
            $nomor = $start + 1;
            foreach($query_data as $val) {
				
                $response['data'][] = [
                    $nomor,
                    $val->user->nama,
                    $val->title,
                    $val->note,
                    date('d/m/y H:i:s',strtotime($val->created_at)),
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
        $countNew = 0;
        foreach($data as $row) {
            $dbtimestamp = strtotime($row->created_at);
            $isNew = '';
            if (time() - $dbtimestamp <= (15 * 60)) {
                $countNew++;
                $isNew = '1';
            }
            $notif[] = [
                'id'    => $row->id,
                'note'  => $row->note,
                'time'  => $row->getTimeAgo(),
                'is_new'=> $isNew,
            ];
        }
        $data = [
            'count_new' => $countNew,
            'data'      => $notif,
        ];
        return response()->json($data);
    }
}
