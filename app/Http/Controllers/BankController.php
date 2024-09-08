<?php

namespace App\Http\Controllers;

use App\Helpers\CustomHelper;
use App\Http\Controllers\Controller;
use App\Models\Bank;
use App\Models\ProjectType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class BankController extends Controller
{
    public function index()
    {
        $data = [
            'title'         => 'Rekening Bank',
            'content'       => 'bank',
        ];

        return view('layouts.index', ['data' => $data]);
    }

    public function datatable(Request $request){
        $column = [
            'id',
            'code',
            'name',
            'no',
            'bank',
            'branch',
            'status',
        ];

        $start  = $request->start;
        $length = $request->length;
        $order  = $column[$request->input('order.0.column')];
        $dir    = $request->input('order.0.dir');
        $search = $request->input('search.value');

        $total_data = Bank::count();
        
        $query_data = Bank::where(function($query) use ($search, $request) {
                if($search) {
                    $query->where('code', 'like', "%$search%")
                        ->orWhere('name','like',"%$search%")
                        ->orWhere('no','like',"%$search%")
                        ->orWhere('bank','like',"%$search%");
                }
            })
            ->offset($start)
            ->limit($length)
            ->orderBy($order, $dir)
            ->get();

        $total_filtered = Bank::where(function($query) use ($search, $request) {
                if($search) {
                    $query->where('code', 'like', "%$search%")
                        ->orWhere('name','like',"%$search%")
                        ->orWhere('no','like',"%$search%")
                        ->orWhere('bank','like',"%$search%");
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
                    $val->no,
                    $val->bank,
                    $val->branch,
                    $val->statusBadge(),
                    '
                        <a href="javascript:void(0);" class="btn btn-warning btn-sm content-icon" onclick="edit('.$val->id.')"><i class="fa fa-edit"></i></a>
                        <a href="javascript:void(0);" class="btn btn-danger btn-sm content-icon" onclick="destroy('.$val->id.')"><i class="fa fa-trash"></i></a>
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
        $data = Bank::find($request->code);
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
                'code'		    => $request->temp ? [Rule::unique('banks', 'code')->ignore($request->temp)] : 'unique:banks,code',
                'name'          => 'required',
                'no'            => 'required',
                'bank'          => 'required',
                'branch'        => 'required',
            ], [
                'code.unique'       => 'Kode telah terpakai.',
                'name.required'     => 'Nama tidak boleh kosong.',
                'no.required'       => 'Nomor rekening tidak boleh kosong.',
                'bank.required'     => 'Bank tidak boleh kosong.',
                'branch.required'   => 'Cabang tidak boleh kosong.',
            ]);

            if($validation->fails()) {
                $response = [
                    'status' => 422,
                    'error'  => $validation->errors()
                ];
            } else {
                if($request->temp){
                    $query = Bank::find($request->temp);
                    $query->code            = $request->code;
                    $query->name            = $request->name;
                    $query->no              = $request->no;
                    $query->bank            = $request->bank;
                    $query->branch          = $request->branch;
                    $query->status          = $request->status ?? NULL;
                    $query->save();
                    CustomHelper::saveLog($query->getTable(),$query->id,'Update data bank '.$query->code,'Pengguna '.session('bo_name').' telah mengubah data bank no '.$query->code);
                }else{
                    $query = Bank::create([
                        'code'              => $request->code,
                        'name'              => $request->name,
                        'no'                => $request->no,
                        'bank'              => $request->bank,
                        'branch'            => $request->branch,
                        'status'            => $request->status ?? NULL,
                    ]);
                    CustomHelper::saveLog($query->getTable(),$query->id,'Tambah baru data bank '.$query->code,'Pengguna '.session('bo_name').' telah manambahkan baru data bank no '.$query->code);
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
        $query = Bank::find($request->code);
		
        if($query->delete()) {
            CustomHelper::saveLog($query->getTable(),$query->id,'Delete data bank '.$query->code,'Pengguna '.session('bo_name').' telah menghapus data bank no '.$query->code);

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