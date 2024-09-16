<?php

namespace App\Http\Controllers;

use App\Helpers\CustomHelper;
use App\Http\Controllers\Controller;
use App\Models\Approval;
use App\Models\Customer;
use Illuminate\Http\Request;
use Svg\Tag\Rect;

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

    public function getCountApproval(Request $request){
        $count = Approval::where('to_user_id',session('bo_id'))->where('approve_status','1')->count();
        return response()->json($count);
    }

    public function detail(Request $request,$code){
        $data = Approval::where('code',$code)->where('to_user_id',session('bo_id'))->first();
        if($data){

            $data = [
                'title'         => 'Detail Persetujuan '.$data->lookable->code,
                'data'          => $data,
                'content'       => 'approval.'.$data->url,
            ];
    
            return view('layouts.index', ['data' => $data]);
        }else{
            abort(404);
        }
    }

    public function approve(Request $request){
        $data = Approval::where('code',$request->code)->where('approve_status','1')->first();
        if($data){
            if($request->type == 'agree'){
                $data->update([
                    'approve_status'    => '2',
                    'approve_date'      => now(),
                    'approve_note'      => $request->note,
                ]);
                $nextlevel = $data->approve_level + 1;
                $data2 = Approval::where('lookable_type',$data->lookable_type)->where('lookable_id',$data->lookable_id)->whereNull('approve_status')->where('approve_level',$nextlevel)->get();
                if($data2){
                    $message = '';
                    if($data->url == 'invoice'){
                        $message = 'Dear Bapak/Ibu Pimpinan. Ijin menginformasikan bahwa dokumen Invoice No. '.$data->code.' telah dibayarkan dengan nomor kwitansi : '.$data->lookable->receipt_code.', mohon persetujuannya dengan menekan link terlampir : ';
                    }
                    foreach($data2 as $row){
                        $row->update([
                            'approve_status'    => '1'
                        ]);
                        if($row->toUser()->exists()){
                            if($row->toUser->phone){
                                CustomHelper::sendWhatsapp($row->toUser->phone,$message.' '.env('APP_URL').'/persetujuan/detail/'.$row->code);
                            }
                        }
                    }
                }else{
                    $data->lookable->update([
                        'status'    => '3'
                    ]);
                }
                CustomHelper::saveLog($data->lookable_type,$data->lookable_id,'Data dokumen no '.$data->lookable->code.' telah disetujui.','Pengguna '.session('bo_name').' telah menyetujui data dokumen no '.$data->lookable->code);
            }else{
                $data->lookable->update([
                    'status'    => '5'
                ]);
                $data->update([
                    'approve_status'    => '3',
                    'approve_date'      => now(),
                    'approve_note'      => $request->note,
                ]);
                CustomHelper::saveLog($data->lookable_type,$data->lookable_id,'Data dokumen no '.$data->lookable->code.' telah ditolak/revisi.','Pengguna '.session('bo_name').' telah menolak/revisi data dokumen no '.$data->lookable->code);
            }
            

            $response = [
                'status'    => 200,
                'data'      => $data,
                'message'   => 'Data berhasil disimpan, halaman akan dimuat ulang.'
            ];
        }else{
            $response = [
                'status'    => 500,
                'message'   => 'Mohon maaf, data approval tidak ditemukan.'
            ];
        }
        return response()->json($response);
    }
}
