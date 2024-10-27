<?php

namespace App\Http\Controllers;

use App\Helpers\CustomHelper;
use App\Http\Controllers\Controller;
use App\Models\Andalalin;
use App\Models\AndalalinDetail;
use App\Models\Invoice;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use Process;
use Illuminate\Support\Facades\Storage;

class AndalalinController extends Controller
{
    public function index()
    {
        $data = [
            'title'         => 'Dokumen Andalalin',
            'content'       => 'andalalin',
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
            'note',
            'status',
        ];

        $start  = $request->start;
        $length = $request->length;
        $order  = $column[$request->input('order.0.column')];
        $dir    = $request->input('order.0.dir');
        $search = $request->input('search.value');

        $total_data = Andalalin::count();
        
        $query_data = Andalalin::where(function($query) use ($search, $request) {
                if($search) {
                    $query->where(function($query) use ($search, $request) {
                        $query->where('code', 'like', "%$search%")
                            ->orWhere('note', 'like', "%$search%")
                            ->orWhereHas('project',function($query) use ($search){
                                $query->whereHas('customer', function($query) use ($search){
                                    $query->where('code','like',"%$search%")
                                        ->orWhere('name','like',"%$search%");
                                })->orWhere('code', 'like', "%$search%");
                            });
                    });
                }
            })
            ->offset($start)
            ->limit($length)
            ->orderBy($order, $dir)
            ->get();

        $total_filtered = Andalalin::where(function($query) use ($search, $request) {
                if($search) {
                    $query->where(function($query) use ($search, $request) {
                        $query->where('code', 'like', "%$search%")
                            ->orWhere('note', 'like', "%$search%")
                            ->orWhereHas('project',function($query) use ($search){
                                $query->whereHas('customer', function($query) use ($search){
                                    $query->where('code','like',"%$search%")
                                        ->orWhere('name','like',"%$search%");
                                })->orWhere('code', 'like', "%$search%");
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
                    $val->note,
                    $val->andalalinDetail()->count(),
                    $val->statusBadge(),
                    '
                        <a href="javascript:void(0);" class="btn btn-secondary btn-sm content-icon" onclick="detail(`'.CustomHelper::encrypt($val->code).'`)"><i class="fa fa-info-circle"></i></a>
                        <a href="javascript:void(0);" class="btn btn-info btn-sm content-icon" data-toggle="tooltip" data-placement="top" title="Upload Bukti" onclick="showUpload(`'.CustomHelper::encrypt($val->code).'`)"><i class="fa fa-upload"></i></a>
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
                'code'		            => $request->temp ? ['required', Rule::unique('andalalins', 'code')->ignore(CustomHelper::decrypt($request->temp),'code')] : 'required|unique:andalalins,code',
                'project_id'            => 'required',
                'post_date'             => 'required',
            ], [
                'code.required'             => 'Kode tidak boleh kosong.',
                'code.unique'               => 'Kode telah dipakai.',
                'project_id.required'       => 'Project tidak boleh kosong.',
                'post_date.required'        => 'Tgl. post tidak boleh kosong.',
            ]);

            if($validation->fails()) {
                $response = [
                    'status' => 422,
                    'error'  => $validation->errors()
                ];
            } else {
                if($request->temp){
                    $query = Andalalin::where('code',CustomHelper::decrypt($request->temp))->first();

                    if($query->status == '3'){
                        return response()->json([
                            'status'    => 500,
                            'message'   => 'Ups. Dokumen Andalalin telah SELESAI, anda tidak bisa melakukan perubahan.'
                        ]);
                    }

                    $query->user_id         = session('bo_id');
                    $query->code            = $request->code;
                    $query->project_id      = $request->project_id;
                    $query->post_date       = $request->post_date;
                    $query->note            = $request->note;
                    $query->status          = '1';
                    $query->save();
                    CustomHelper::saveLog($query->getTable(),$query->id,'Update data dokumen andalalin '.$query->code,'Pengguna '.session('bo_nama').' telah mengubah data dokumen andalalin no '.$query->code);
                }else{
                    $query = Andalalin::create([
                        'user_id'         => session('bo_id'),
                        'code'            => $request->code,
                        'project_id'      => $request->project_id,
                        'post_date'       => $request->post_date,
                        'note'            => $request->note,
                        'status'          => '1',
                    ]);
                    CustomHelper::saveLog($query->getTable(),$query->id,'Tambah baru data dokumen andalalin '.$query->code,'Pengguna '.session('bo_nama').' telah manambahkan baru data dokumen andalalin no '.$query->code);
                }
                
                if($query) {
                    CustomHelper::sendApproval($query->getTable(),$query->id,'dokumen_andalalin');
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
        $data = Andalalin::where('code',CustomHelper::decrypt($request->code))->first();
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
        $data = Andalalin::where('code',CustomHelper::decrypt($request->code))->first();
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
        $query = Andalalin::where('code',CustomHelper::decrypt($request->code))->first();
        if($query){
            if($query->status == '1'){
                CustomHelper::saveLog($query->getTable(),$query->id,'Dokumen Andalalin nomor '.$query->code.' telah dihapus.','Pengguna '.session('bo_nama').' telah menghapus data dokumen andalalin no '.$query->code);

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

    public function showUpload(Request $request){
        $data = Andalalin::where('code',CustomHelper::decrypt($request->code))->first();
        if($data){
            $images = [];

            foreach($data->andalalinDetail as $rowfile){
                $images[] = [
                    'file'      => $rowfile->getFile(),
                    'code'      => CustomHelper::encrypt($rowfile->code),
                    'name'      => $rowfile->name,
                ];
            }

            $response = [
                'status'    => 200,
                'code'      => $data->code,
                'data'      => $images,
            ];
        }else{
            $response = [
                'status'  => 500,
                'message' => 'Data tidak ditemukan.'
            ];
        }

        return response()->json($response);
    }

    public function check(Request $request){		
        $query = AndalalinDetail::where('name',$request->name)->first();
        
        if($query){
            return response()->json([
                'status'		=> 0,
            ]);
        }else{
            return response()->json([
                'status'		=> 1,
            ]);
        }
	}

    public function upload(Request $request){
        $validation = Validator::make($request->all(), [
			'file' 				=> 'required',
			'code'	            => 'required',
		]);

        if($validation->fails()) {
            $response = [
                'status' => 422,
                'error'  => $validation->errors()
            ];
        } else {
            $query = Andalalin::where('code',CustomHelper::decrypt($request->code))->first();

            if($query){
                if($query->status == '1'){
                    $querydetail = AndalalinDetail::create([
                        'andalalin_id'      => $query->id,
                        'code'	            => strtoupper(Str::random(15)),
                        'name'              => $request->file('file')->getClientOriginalName(),
                        'file_location'	    => $request->file('file') ? $request->file('file')->store('public/andalalin') : NULL
                    ]);
                    $newimage = [
                        'file'      => $querydetail->getFile(),
                        'code'      => CustomHelper::encrypt($querydetail->code),
                        'name'      => $querydetail->name,
                    ];
                    CustomHelper::saveLog($query->getTable(),$query->id,'Tambah baru data file dokumen andalalin '.$query->code,'Pengguna '.session('bo_nama').' telah manambahkan baru data file dokumen andalalin no '.$query->code);
                    $response = [
                        'status'		=> 200,
                        'message'		=> 'Data berhasil di upload.',
                        'newimage'      => $newimage,
                    ];
                }else{
                    $response = [
                        'status'		=> 500,
                        'message'		=> 'Maaf, status dokumen sudah diluar perubahan.'
                    ];
                }
            }else{
                $response = [
                    'status'		=> 500,
                    'message'		=> 'Maaf, data survei tidak ditemukan.'
                ];
            }
        }

        return response()->json($response);
	}

    public function destroyFile(Request $request){
		$data = AndalalinDetail::where('code',CustomHelper::decrypt($request->id))->first();
		
		$data->deleteFile();
		
		if($data->delete()){
            CustomHelper::saveLog($data->getTable(),$data->id,'Menghapus data file dokumen andalalin '.$data->code,'Pengguna '.session('bo_nama').' telah menghapus data file dokumen andalalin no '.$data->code);
			return response()->json([
				'status'	=> 200,
				'message'	=> 'File berhasil dihapus.' 
			]);
		}else{
			return response()->json([
				'status'	=> 422,
				'message'	=> 'File tidak ditemukan.'
			]);
		}
	}
}
