<?php

namespace App\Http\Controllers;

use App\Helpers\CustomHelper;
use App\Http\Controllers\Controller;
use App\Mail\SendMail;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index()
    {
        $data = [
            'title'         => 'Pengguna',
            'content'       => 'user',
        ];

        return view('layouts.index', ['data' => $data]);
    }

    public function datatable(Request $request){
        $column = [
            'id',
            'nama',
            'nik',
            'email',
            'type',
            'phone',
            'status',
        ];

        $start  = $request->start;
        $length = $request->length;
        $order  = $column[$request->input('order.0.column')];
        $dir    = $request->input('order.0.dir');
        $search = $request->input('search.value');

        $total_data = User::count();
        
        $query_data = User::where(function($query) use ($search, $request) {
                if($search) {
                    $query->where('nik', 'like', "%$search%")
                        ->orWhere('nama','like',"%$search%")
                        ->orWhere('email','like',"%$search%");
                }
            })
            ->offset($start)
            ->limit($length)
            ->orderBy($order, $dir)
            ->get();

        $total_filtered = User::where(function($query) use ($search, $request) {
                if($search) {
                    $query->where('nik', 'like', "%$search%")
                        ->orWhere('nama','like',"%$search%")
                        ->orWhere('email','like',"%$search%");
                }
            })
            ->count();

        $response['data'] = [];
        if($query_data <> FALSE) {
            $nomor = $start + 1;
            foreach($query_data as $val) {
				
                $response['data'][] = [
                    $nomor,
                    $val->nama,
                    $val->nik,
                    $val->email,
                    $val->type(),
                    $val->phone ?? 'Belum diset',
                    $val->statusBadge(),
                    '
                        <a href="javascript:void(0);" class="btn btn-secondary btn-sm content-icon" onclick="updatePassword('.$val->id.')"><i class="fa fa-unlock"></i></a>
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

    public function updatePassword(Request $request){
        $user = User::where('id',$request->id)->where('status','1')->first();

        if($user){
            $pass = Str::random(6);
            $user->update([
                'password'  => bcrypt($pass),
                'code'      => base64_encode($pass),
            ]);

            $data = [
                'subject'   => 'Reset Password',
                'view'      => 'mail.reset',
                'user'      => $user->toArray(),
            ];

            Mail::to($user->email)->send(new SendMail($data));

            return response()->json([
                'status'    => 200,
                'message'   => 'Password berhasil dikirimkan!'
            ]);
        }else{
            $response = [
                'status' => 500,
                'error'  => 'Data tidak ditemukan.'
            ];
        }

        return response()->json($response);
    }

    public function show(Request $request){
        $data = User::find($request->code);
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
                'nik'		    => $request->temp ? [Rule::unique('users', 'nik')->ignore($request->temp)] : 'unique:users,nik',
            ], [
                'nik.unique'    => 'NIK telah terpakai.',
            ]);

            if($validation->fails()) {
                $response = [
                    'status' => 422,
                    'error'  => $validation->errors()
                ];
            } else {
                if($request->temp){
                    $query = User::find($request->temp);
                    $query->nama            = $request->name;
                    $query->nik             = $request->nik;    
                    $query->email           = $request->email;
                    $query->type            = $request->type;
                    $query->phone           = $request->phone;
                    $query->status          = $request->status ?? NULL;
                    $query->save();
                    CustomHelper::saveLog($query->getTable(),$query->id,'Update data user '.$query->nik,'Pengguna '.session('bo_name').' telah mengubah data user no '.$query->nik);
                }else{
                    $query = User::create([
                        'nama'              => $request->name,
                        'nik'               => $request->nik,    
                        'email'             => $request->email,
                        'type'              => $request->type,
                        'phone'             => $request->phone,
                        'status'            => $request->status ?? '2',
                    ]);
                    CustomHelper::saveLog($query->getTable(),$query->id,'Tambah baru data user '.$query->nik,'Pengguna '.session('bo_name').' telah manambahkan baru data user no '.$query->nik);
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
        $query = User::find($request->code);
		
        if($query->delete()) {
            CustomHelper::saveLog($query->getTable(),$query->id,'Delete data user '.$query->nik,'Pengguna '.session('bo_name').' telah menghapus data user no '.$query->nik);

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