<?php

namespace App\Http\Controllers;

use App\Helpers\CustomHelper;
use App\Http\Controllers\Controller;
use App\Mail\SendMail;
use App\Models\Purpose;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class PurposeController extends Controller
{
    public function index()
    {
        $data = [
            'title'         => 'Peruntukan',
            'content'       => 'purpose',
        ];

        return view('layouts.index', ['data' => $data]);
    }

    public function datatable(Request $request){
        $column = [
            'id',
            'code',
            'status',
        ];

        $start  = $request->start;
        $length = $request->length;
        $order  = $column[$request->input('order.0.column')];
        $dir    = $request->input('order.0.dir');
        $search = $request->input('search.value');

        $total_data = Purpose::count();
        
        $query_data = Purpose::where(function($query) use ($search, $request) {
                if($search) {
                    $query->where('code', 'like', "%$search%")
                        ->orWhere('nama','like',"%$search%");
                }
            })
            ->offset($start)
            ->limit($length)
            ->orderBy($order, $dir)
            ->get();

        $total_filtered = Purpose::where(function($query) use ($search, $request) {
                if($search) {
                    $query->where('code', 'like', "%$search%")
                        ->orWhere('nama','like',"%$search%");
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
                    $val->name,
                    $val->statusBadge(),
                    '
                        <a href="javascript:void(0);" class="btn btn-secondary btn-sm content-icon" onclick="updatePassword('.$val->id.')"><i class="fa fa-unlock"></i></a>
                        <a href="javascript:void(0);" class="btn btn-warning btn-sm content-icon" onclick="edit('.$val->id.')"><i class="fa fa-edit"></i></a>
                        <a href="javascript:void(0);" class="btn btn-danger btn-sm content-icon" onclick="destroy('.$val->id.')"><i class="fa fa-times"></i></a>
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

    public function show(Request $request){
        $data = Purpose::find($request->code);
        if($data){
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

    public function create(Request $request){
        DB::beginTransaction();
        try {
            $validation = Validator::make($request->all(), [
                'code'		    => $request->temp ? [Rule::unique('purposes', 'code')->ignore($request->temp)] : 'unique:purposes,code',
                'name'          => 'required',
            ], [
                'code.unique'   => 'Kode telah terpakai.',
                'name.required' => 'Nama tidak boleh kosong.'
            ]);

            if($validation->fails()) {
                $response = [
                    'status' => 422,
                    'error'  => $validation->errors()
                ];
            } else {
                if($request->temp){
                    $query = Purpose::find($request->temp);
                    $query->code            = $request->code;
                    $query->name            = $request->name;    
                    $query->status          = $request->status ?? NULL;
                    $query->save();
                    CustomHelper::saveLog($query->getTable(),$query->id,'Update data peruntukan '.$query->code,'Pengguna '.session('bo_name').' telah mengubah data peruntukan no '.$query->code);
                }else{
                    $query = Purpose::create([
                        'code'              => $request->code,
                        'name'              => $request->name,    
                        'status'            => $request->status ?? NULL,
                    ]);
                    CustomHelper::saveLog($query->getTable(),$query->id,'Tambah baru data peruntukan '.$query->code,'Pengguna '.session('bo_name').' telah manambahkan baru data peruntukan no '.$query->code);
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

    public function destroy(Request $request){
        $query = Purpose::find($request->code);
		
        if($query->delete()) {
            CustomHelper::saveLog($query->getTable(),$query->id,'Delete data peruntukan '.$query->code,'Pengguna '.session('bo_name').' telah menghapus data peruntukan no '.$query->code);

            $response = [
                'status'  => 200,
                'message' => 'Data deleted successfully.'
            ];
        } else {
            $response = [
                'status'  => 500,
                'message' => 'Data failed to delete.'
            ];
        }

        return response()->json($response);
    }
}