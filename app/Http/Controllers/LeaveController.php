<?php

namespace App\Http\Controllers;

use App\Helpers\CustomHelper;
use App\Http\Controllers\Controller;
use App\Models\Leave;
use App\Models\LeaveDetail;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class LeaveController extends Controller
{
    public function index()
    {
        $data = [
            'title'         => 'Cuti',
            'content'       => 'leave',
        ];

        return view('layouts.index', ['data' => $data]);
    }

    public function datatable(Request $request){
        $column = [
            'id',
            'code',
            'user_id',
            'employee_id',
			'post_date',
            'note',
            'status',
        ];

        $start  = $request->start;
        $length = $request->length;
        $order  = $column[$request->input('order.0.column')];
        $dir    = $request->input('order.0.dir');
        $search = $request->input('search.value');

        $total_data = Leave::count();
        
        $query_data = Leave::where(function($query) use ($search, $request) {
                if($search) {
                    $query->where(function($query) use ($search, $request) {
                        $query->where('code', 'like', "%$search%")
                            ->orWhere('note', 'like', "%$search%")
                            ->orWhereHas('user',function($query) use ($search){
                                $query->where('nama','like',"%$search%")
                                    ->orWhere('nik','like',"%$search%");
                            })
                            ->orWhereHas('employee',function($query) use ($search){
                                $query->where('nama','like',"%$search%")
                                    ->orWhere('nik','like',"%$search%");
                            });
                    });
                }
            })
            ->offset($start)
            ->limit($length)
            ->orderBy($order, $dir)
            ->get();

        $total_filtered = Leave::where(function($query) use ($search, $request) {
                if($search) {
                    $query->where(function($query) use ($search, $request) {
                        $query->where('code', 'like', "%$search%")
                            ->orWhere('note', 'like', "%$search%")
                            ->orWhereHas('user',function($query) use ($search){
                                $query->where('nama','like',"%$search%")
                                    ->orWhere('nik','like',"%$search%");
                            })
                            ->orWhereHas('employee',function($query) use ($search){
                                $query->where('nama','like',"%$search%")
                                    ->orWhere('nik','like',"%$search%");
                            });
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
                    $val->code,
                    $val->user->nama,
                    $val->employee->nama,
                    date('d/m/Y',strtotime($val->post_date)),
                    $val->note,
                    $val->statusBadge(),
                    $val->leaveDetail()->count(),
                    '
                        <a href="javascript:void(0);" class="btn btn-secondary btn-sm content-icon" onclick="detail(`'.CustomHelper::encrypt($val->code).'`)"><i class="fa fa-info-circle"></i></a>
                        <a href="javascript:void(0);" class="btn btn-warning btn-sm content-icon" onclick="edit(`'.CustomHelper::encrypt($val->code).'`)"><i class="fa fa-edit"></i></a>
                        <a href="javascript:void(0);" class="btn btn-danger btn-sm content-icon" onclick="destroy(`'.CustomHelper::encrypt($val->code).'`)"><i class="fa fa-trash"></i></a>
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

    public function create(Request $request){
        /* DB::beginTransaction();
        try { */
            $validation = Validator::make($request->all(), [
                'employee_id'           => 'required',
                'post_date'             => 'required',
                'arr_date'              => 'required|array',
            ], [
                'employee_id.required'      => 'Karyawan tidak boleh kosong.',
                'post_date.required'        => 'Tgl. post tidak boleh kosong.',
                'arr_date.required'         => 'Tgl. pengajuan cuti tidak boleh kosong.',
                'arr_date.array'            => 'Tgl. pengajuan cuti harus dalam array.',
            ]);

            if($validation->fails()) {
                $response = [
                    'status' => 422,
                    'error'  => $validation->errors()
                ];
            } else {
                if($request->temp){
                    $query = Leave::where('code',CustomHelper::decrypt($request->temp))->first();

                    if($query->status == '3'){
                        return response()->json([
                            'status'    => 500,
                            'message'   => 'Ups. Cuti telah SELESAI, anda tidak bisa melakukan perubahan.'
                        ]);
                    }

                    $query->user_id         = session('bo_id');
                    $query->code            = $request->code;
                    $query->employee_id     = $request->employee_id;
                    $query->post_date       = $request->post_date;
                    $query->note            = $request->note;
                    $query->status          = '3';
                    $query->save();

                    $query->leaveDetail()->delete();
                    
                    CustomHelper::saveLog($query->getTable(),$query->id,'Update data cuti karyawan '.$query->code,'Pengguna '.session('bo_nama').' telah menambahkan cuti karyawan no '.$query->code);
                }else{
                    $query = Leave::create([
                        'user_id'           => session('bo_id'),
                        'code'              => strtoupper(Str::random(10)),
                        'employee_id'       => $request->employee_id,
                        'post_date'         => $request->post_date,
                        'note'              => $request->note,
                        'status'            => '3',
                    ]);
                    CustomHelper::saveLog($query->getTable(),$query->id,'Tambah baru data cuti karyawan '.$query->code,'Pengguna '.session('bo_nama').' telah manambahkan baru data sidang no '.$query->code);
                }
                
                if($query) {

                    if($request->arr_date){
                        foreach($request->arr_date as $row){
                            LeaveDetail::create([
                                'leave_id'  => $query->id,
                                'date'      => $row,
                            ]);
                        }
                    }

                    $response = [
                        'status'  => 200,
                        'message' => 'Data berhasil disimpan.'
                    ];
                } else {
                    $response = [
                        'status'  => 500,
                        'message' => 'Data gagal disimpan.'
                    ];
                }
            }
            /* DB::commit(); */
		    return response()->json($response);
        /* }catch(\Exception $e){
            DB::rollback();
        } */
    }

    public function show(Request $request){
        $data = Hearing::where('code',CustomHelper::decrypt($request->code))->first();
        if($data){
            $data['project_code'] = $data->project->code.' - '.$data->project->name.' - '.$data->project->customer->name;
            $response = [
                'status'    => 200,
                'data'      => $data,
            ];
        }else{
            $response = [
                'status'  => 500,
                'message' => 'Data tidak ditemukan.'
            ];
        }

        return response()->json($response);
    }

    public function detail(Request $request){
        $data = Hearing::where('code',CustomHelper::decrypt($request->code))->first();
        if($data){

            $html = '';

            if($data->approval()->exists()){
                $html = '<table class="table table-responsive-md">
                        <thead>
                            <tr>
                                <th><strong>#</strong></th>
                                <th><strong>APPROVER</strong></th>
                                <th><strong>LEVEL</strong></th>
                                <th><strong>TGL.APPROVE</strong></th>
                                <th><strong>STATUS</strong></th>
                                <th><strong>Catatan</strong></th>
                            </tr>
                        </thead><tbody>';

                foreach($data->approval()->orderBy('approve_level')->get() as $key => $row){
                    $html .= '<tr>
                        <td class="text-center">'.($key+1).'</td>
                        <td>'.$row->toUser->nama.'</td>
                        <td>'.$row->approve_level.'</td>
                        <td>'.($row->approve_date ? date('d/m/Y H:i:s',strtotime($row->approve_date)) : '-').'</td>
                        <td>'.$row->approveStatus().'</td>
                        <td>'.$row->approve_note.'</td>
                    </tr>';
                }

                $html .= '</tbody></table>';
            }

            $response = [
                'status'    => 200,
                'data'      => $data,
                'html'      => $html,
            ];
        }else{
            $response = [
                'status'  => 500,
                'message' => 'Data tidak ditemukan.'
            ];
        }

        return response()->json($response);
    }

    public function destroy(Request $request){
        $query = Hearing::where('code',CustomHelper::decrypt($request->code))->first();
        if($query){
            if($query->status == '1'){
                CustomHelper::saveLog($query->getTable(),$query->id,'Sidang nomor '.$query->code.' telah dihapus.','Pengguna '.session('bo_nama').' telah menghapus data sidang no '.$query->code);

                $query->deleteFile();
                $query->approval()->delete();
                $query->delete();

                $response = [
                    'status'    => 200,
                    'message'   => 'Data berhasil dihapus.',
                ];
            }else{
                $response = [
                    'status'    => 500,
                    'message'   => 'Hanya dokumen MENUNGGU yang bisa dihapus.',
                ];
            }
        }else{
            $response = [
                'status'  => 500,
                'message' => 'Data tidak ditemukan.'
            ];
        }

        return response()->json($response);
    }
}
