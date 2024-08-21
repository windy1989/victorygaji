<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Mail\SendMail;
use App\Models\Customer;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

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
            'nama',
            'nik',
            'email',
            'type',
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
                    $query->where('nik', 'like', "%$search%")
                        ->orWhere('nama','like',"%$search%")
                        ->orWhere('email','like',"%$search%");
                }
            })
            ->offset($start)
            ->limit($length)
            ->orderBy($order, $dir)
            ->get();

        $total_filtered = Customer::where(function($query) use ($search, $request) {
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
                    $val->status(),
                    '
						<span style="font-size:25px;" data-toggle="tooltip" data-placement="top" title="Reset dan email password">
							<a href="javascript:void(0);" onclick="updatePassword('.$val->id.')"><i class="fa fa-unlock text-info"></i></a>
                        </span>
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
}