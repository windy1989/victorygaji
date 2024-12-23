<?php

namespace App\Http\Controllers;

use App\Helpers\CustomHelper;
use App\Http\Controllers\Controller;
use App\Mail\SendMail;
use App\Models\Customer;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class CustomerController extends Controller
{
    public function index()
    {
        $data = [
            'title'         => 'Pelanggan',
            'content'       => 'customer',
        ];

        return view('layouts.index', ['data' => $data]);
    }

    public function datatable(Request $request){
        $column = [
            'id',
            'code',
            'name',
            'email',
            'owner_name',
            'pic',
            'owner_id_card',
            'company_name',
            'document_no',
            'address',
            'city',
            'gender',
            'phone',
            'type_body',
            'note',
            'status',
        ];

        $start  = $request->start;
        $length = $request->length;
        $order  = $column[$request->input('order.0.column')];
        $dir    = $request->input('order.0.dir');
        $search = $request->input('search.value');

        $total_data = Customer::count();
        
        $query_data = Customer::where(function($query) use ($search, $request) {
                if($search) {
                    $query->where('code', 'like', "%$search%")
                        ->orWhere('name','like',"%$search%")
                        ->orWhere('email','like',"%$search%")
                        ->orWhere('owner_name','like',"%$search%")
                        ->orWhere('pic','like',"%$search%");
                }
            })
            ->offset($start)
            ->limit($length)
            ->orderBy($order, $dir)
            ->get();

        $total_filtered = Customer::where(function($query) use ($search, $request) {
                if($search) {
                    $query->where('code', 'like', "%$search%")
                        ->orWhere('name','like',"%$search%")
                        ->orWhere('email','like',"%$search%")
                        ->orWhere('owner_name','like',"%$search%")
                        ->orWhere('pic','like',"%$search%");
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
                    $val->email,
                    $val->owner_name,
                    $val->pic,
                    $val->owner_id_card,
                    $val->company_name,
                    $val->document_no,
                    $val->address,
                    $val->city,
                    $val->gender(),
                    $val->phone,
                    $val->typeBody(),
                    $val->note,
                    $val->statusBadge(),
                    $val->logo ? '<a href="'.$val->attachment().'" target="_blank"><i class="flaticon-381-link"></i></a>' : 'Belum diunggah',
                    '
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
                'name'              => 'required',
            ], [
                'name.required'         => 'Nama tidak boleh kosong.',
            ]);

            if($validation->fails()) {
                $response = [
                    'status' => 422,
                    'error'  => $validation->errors()
                ];
            } else {
                if($request->temp){
                    $query = Customer::where('code',CustomHelper::decrypt($request->temp))->first();
                    
                    $desiredPath = '';
                    if($request->has('document')){
                        if($query->logo){
                            if(Storage::exists($query->logo)){
                                Storage::delete($query->logo);
                            }
                        }
                        $desiredPath = $request->file('document')->store('public/customer');
                    }else{
                        $desiredPath = $query->logo;
                    }

                    $query->name            = $request->name;        
                    $query->email           = $request->email;
                    $query->owner_name      = $request->owner_name;
                    $query->pic             = $request->pic;
                    $query->owner_id_card   = $request->owner_id_card;
                    $query->company_name    = $request->company_name;
                    $query->document_no     = $request->document_no;
                    $query->address         = $request->address;
                    $query->city            = $request->city;
                    $query->gender          = $request->gender;
                    $query->phone           = $request->phone;
                    $query->type_body       = $request->type_body;
                    $query->note            = $request->note;
                    $query->logo            = $desiredPath;
                    $query->status          = $request->status ?? '2';
                    $query->save();
                    CustomHelper::saveLog($query->getTable(),$query->id,'Update data customer '.$query->code,'Pengguna '.session('bo_nama').' telah mengubah data pelanggan no '.$query->code);
                }else{
                    $query = Customer::create([
                        'code'              => $request->code ?? Customer::generateCode(),
                        'name'              => $request->name,         
                        'email'             => $request->email,
                        'owner_name'        => $request->owner_name,
                        'pic'               => $request->pic,
                        'owner_id_card'     => $request->owner_id_card,
                        'company_name'      => $request->company_name,
                        'document_no'       => $request->document_no,
                        'address'           => $request->address,
                        'city'              => $request->city,
                        'gender'            => $request->gender,
                        'phone'             => $request->phone,
                        'type_body'         => $request->type_body,
                        'note'              => $request->note,
                        'logo'              => $request->file('document') ? $request->file('document')->store('public/customer') : NULL,
                        'status'            => $request->status ?? '2',
                    ]);
                    CustomHelper::saveLog($query->getTable(),$query->id,'Tambah baru data customer '.$query->code,'Pengguna '.session('bo_nama').' telah manambahkan baru data pelanggan no '.$query->code);
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
        $data = Customer::where('code',CustomHelper::decrypt($request->code))->first();
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

    public function destroy(Request $request){
        $query = Customer::where('code',CustomHelper::decrypt($request->code))->first();
		
        if($query->delete()) {
            CustomHelper::saveLog($query->getTable(),$query->id,'Delete data customer '.$query->code,'Pengguna '.session('bo_nama').' telah menghapus data pelanggan no '.$query->code);

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