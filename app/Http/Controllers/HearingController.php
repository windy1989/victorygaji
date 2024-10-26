<?php

namespace App\Http\Controllers;

use App\Helpers\CustomHelper;
use App\Http\Controllers\Controller;
use App\Models\Hearing;
use App\Models\Invoice;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use Process;
use Illuminate\Support\Facades\Storage;

class HearingController extends Controller
{
    public function index()
    {
        $data = [
            'title'         => 'Sidang',
            'content'       => 'hearing',
        ];

        return view('layouts.index', ['data' => $data]);
    }

    public function datatable(Request $request){
        $column = [
            'id',
            'code',
            'user_id',
            'project_id',
			'post_date',
            'no_hearing',
            'no_recomendation',
            'start_date',
            'finish_date',
            'note',
            'status',
        ];

        $start  = $request->start;
        $length = $request->length;
        $order  = $column[$request->input('order.0.column')];
        $dir    = $request->input('order.0.dir');
        $search = $request->input('search.value');

        $total_data = Hearing::count();
        
        $query_data = Hearing::where(function($query) use ($search, $request) {
                if($search) {
                    $query->where(function($query) use ($search, $request) {
                        $query->where('code', 'like', "%$search%")
                            ->orWhere('no_hearing', 'like', "%$search%")
                            ->orWhere('no_recomendation', 'like', "%$search%")
                            ->orWhere('note', 'like', "%$search%")
                            ->orWhereHas('project',function($query) use ($search){
                                $query->whereHas('customer', function($query) use ($search){
                                    $query->where('code','like',"%$search%")
                                        ->orWhere('name','like',"%$search%");
                                });
                            });
                    });
                }
            })
            ->offset($start)
            ->limit($length)
            ->orderBy($order, $dir)
            ->get();

        $total_filtered = Hearing::where(function($query) use ($search, $request) {
                if($search) {
                    $query->where(function($query) use ($search, $request) {
                        $query->where('code', 'like', "%$search%")
                            ->orWhere('no_hearing', 'like', "%$search%")
                            ->orWhere('no_recomendation', 'like', "%$search%")
                            ->orWhere('note', 'like', "%$search%")
                            ->orWhereHas('project',function($query) use ($search){
                                $query->whereHas('customer', function($query) use ($search){
                                    $query->where('code','like',"%$search%")
                                        ->orWhere('name','like',"%$search%");
                                });
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
                    $val->project->project_no.' - '.$val->project->customer->name,
                    date('d/m/Y',strtotime($val->post_date)),
                    $val->no_hearing,
                    $val->no_recomendation,
                    date('d/m/Y',strtotime($val->start_date)),
                    date('d/m/Y',strtotime($val->finish_date)),
                    $val->note,
                    $val->statusBadge(),
                    $val->document ? '<a href="'.$val->attachment().'" target="_blank"><i class="flaticon-381-link"></i></a>' : 'Belum diunggah',
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
        DB::beginTransaction();
        try {
            $validation = Validator::make($request->all(), [
                'code'		            => $request->temp ? ['required', Rule::unique('hearings', 'code')->ignore(CustomHelper::decrypt($request->temp),'code')] : 'required|unique:hearings,code',
                'project_id'            => 'required',
                'post_date'             => 'required',
                'no_hearing'            => 'required',
                'no_recomendation'      => 'required',
                'start_date'            => 'required',
                'finish_date'           => 'required',
            ], [
                'code.required'             => 'Kode tidak boleh kosong.',
                'code.unique'               => 'Kode telah dipakai.',
                'project_id.required'       => 'Project tidak boleh kosong.',
                'post_date.required'        => 'Tgl. post tidak boleh kosong.',
                'no_hearing.required'       => 'No Pendaftaran Sidang tidak boleh kosong.',
                'no_recomendation.required' => 'No Surat Rekomendasi tidak boleh kosong.',
                'start_date.required'       => 'Tgl. Mulai Sidang tidak boleh kosong.',
                'finish_date.required'      => 'Tgl. Selesai Sidang tidak boleh kosong.',
            ]);

            if($validation->fails()) {
                $response = [
                    'status' => 422,
                    'error'  => $validation->errors()
                ];
            } else {
                if($request->temp){
                    $query = Hearing::where('code',CustomHelper::decrypt($request->temp))->first();

                    if($query->status == '3'){
                        return response()->json([
                            'status'    => 500,
                            'message'   => 'Ups. Sidang telah SELESAI, anda tidak bisa melakukan perubahan.'
                        ]);
                    }

                    $desiredPath = '';
                    if($request->has('document')){
                        if($query->document){
                            if(Storage::exists($query->document)){
                                Storage::delete($query->document);
                            }
                        }
                        $imageName = Str::random(35).'.png';
                        $path =storage_path('app/public/hearing/'.$imageName);
                        $newFile = CustomHelper::compress($request->document,$path,50);
                        $basePath = storage_path('app');
                        $desiredPath = explode($basePath.'/', $newFile)[1];
                    }else{
                        $desiredPath = $query->document;
                    }

                    $query->user_id         = session('bo_id');
                    $query->code            = $request->code;
                    $query->project_id      = $request->project_id;
                    $query->post_date       = $request->post_date;
                    $query->no_hearing      = $request->no_hearing;
                    $query->no_recomendation= $request->no_recomendation;
                    $query->start_date      = $request->start_date;
                    $query->finish_date     = $request->finish_date;
                    $query->document        = $desiredPath ?? NULL;
                    $query->note            = $request->note;
                    $query->status          = '2';
                    $query->save();
                    CustomHelper::saveLog($query->getTable(),$query->id,'Update data sidang '.$query->code,'Pengguna '.session('bo_nama').' telah mengubah data sidang no '.$query->code);
                }else{
                    $desiredPath = '';
                    if($request->has('document')){
                        $imageName = Str::random(35).'.png';
                        $path =storage_path('app/public/hearing/'.$imageName);
                        $newFile = CustomHelper::compress($request->document,$path,50);
                        $basePath = storage_path('app');
                        $desiredPath = explode($basePath.'/', $newFile)[1];
                    }
                    $query = Hearing::create([
                        'user_id'           => session('bo_id'),
                        'code'              => $request->code,
                        'project_id'        => $request->project_id,
                        'post_date'         => $request->post_date,
                        'no_hearing'        => $request->no_hearing,
                        'no_recomendation'  => $request->no_recomendation,
                        'start_date'        => $request->start_date,
                        'finish_date'       => $request->finish_date,
                        'document'          => $desiredPath ?? NULL,
                        'note'              => $request->note,
                        'status'            => '2',
                    ]);
                    CustomHelper::saveLog($query->getTable(),$query->id,'Tambah baru data sidang '.$query->code,'Pengguna '.session('bo_nama').' telah manambahkan baru data sidang no '.$query->code);
                }
                
                if($query) {
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
            DB::commit();
		    return response()->json($response);
        }catch(\Exception $e){
            DB::rollback();
        }
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
        $data = Invoice::where('code',CustomHelper::decrypt($request->code))->first();
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
        $query = Invoice::where('code',CustomHelper::decrypt($request->code))->first();
        if($query){
            if($query->status == '1'){
                CustomHelper::saveLog($query->getTable(),$query->id,'Invoice nomor '.$query->code.' telah dihapus.','Pengguna '.session('bo_nama').' telah menghapus data invoice no '.$query->code);

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
