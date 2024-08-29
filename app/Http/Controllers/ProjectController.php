<?php

namespace App\Http\Controllers;

use App\Helpers\CustomHelper;
use App\Http\Controllers\Controller;
use App\Imports\ImportPayroll;
use App\Jobs\QueueMail;
use App\Models\Payroll;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class ProjectController extends Controller
{
    public function index()
    {
        $data = [
            'title'         => 'Proyek',
            'content'       => 'project',
        ];

        return view('layouts.index', ['data' => $data]);
    }

    public function datatable(Request $request){
        $column = [
            'id',
            'nik',
            'name',
            'rekening_bca',
            'bulan',
            'jabatan',
			'gaji_pokok',
            'jumlah_transfer',
            'updated_at',
        ];

        $start  = $request->start;
        $length = $request->length;
        $order  = $column[$request->input('order.0.column')];
        $dir    = $request->input('order.0.dir');
        $search = $request->input('search.value');

        $total_data = Payroll::count();
        
        $query_data = Payroll::where(function($query) use ($search, $request) {
                if($search) {
                    $query->where(function($query) use ($search, $request) {
                        $query->where('nik', 'like', "%$search%")
							->orWhereHas('user', function($query) use ($search){
								$query->where('nama','like',"%$search%")
                                    ->orWhere('email','like',"%$search%");
							});
                    });
                }
            })
            ->offset($start)
            ->limit($length)
            ->orderBy($order, $dir)
            ->get();

        $total_filtered = Payroll::where(function($query) use ($search, $request) {
                if($search) {
                    $query->where(function($query) use ($search, $request) {
                        $query->where('nik', 'like', "%$search%")
                            ->orWhereHas('user', function($query) use ($search){
                                $query->where('nama','like',"%$search%")
                                    ->orWhere('email','like',"%$search%");
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
                    $val->nik,
                    $val->user()->exists() ? $val->user->nama : '-',
                    $val->rekening_bca,
                    $val->bulan,
                    $val->jabatan,
                    number_format($val->gaji_pokok,2,',','.'),
                    number_format($val->jumlah_transfer,2,',','.'),
                    date('d/m/y H:i:s',strtotime($val->updated_at)),
                    '
						<span style="font-size:25px;">
							<a href="javascript:void(0);" onclick="history('.$val->id.')"><i class="fas fa-history text-info"></i></a>
                        </span>
                        <span style="font-size:25px;margin-left:10px;">
							<a href="javascript:void(0);" class="payroll-email" data-payroll="'.$val->id.'"><i class="fas fa-envelope text-warning"></i></a>
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

    public function create(Request $request){
        $validator = Validator::make($request->all(), [
            'file' => [
                'required',
                'mimes:xlsx',
                'max:2048',
                function ($attribute, $value, $fail) {
                    $rows = Excel::toArray([], $value)[0];
                    if (count($rows) < 2) {
                        $fail('Baris minimal harus ada 2.');
                    }
                }
            ]
        ]);

        if ($validator->fails()) {
            $response = [
                'status' => 422,
                'error'  => $validator->errors()
            ];
            return response()->json($response);
        }

        try {
            Excel::import(new ImportPayroll, $request->file('file'));

            return response()->json([
                'status'    => 200,
                'message'   => 'Import sukses!'
            ]);
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures();

            $errors = [];
            foreach ($failures as $failure) {
                $errors[] = [
                    'row' => $failure->row(),
                    'attribute' => $failure->attribute(),
                    'errors' => $failure->errors(),
                    'values' => $failure->values(),
                ];
            }

            $response = [
                'status' => 432,
                'error'  => $errors
            ];

            return response()->json($response);

        } catch (\Exception $e) {
            $response = [
                'status'  => 500,
                'message' => "Data failed to save : ".$e->getMessage()
            ];
            return response()->json($response);
        }
    }

    public function sendEmail(Request $request){

        $payroll = Payroll::find(intval($request->id));

        if($payroll){
            try {

                $data = [
                    'subject'   => 'Slip Gaji - '.$payroll->bulan,
                    'view'      => 'mail.slip',
                    'result'    => $payroll->toArray(),
                    'user'      => $payroll->user->toArray(),
                ];
                
                QueueMail::dispatch($payroll->user->email,$payroll->user->nama,$data);

                if($payroll->telepon){
                    CustomHelper::sendWhatsapp($payroll->telepon,'Selamat gaji anda bulan '.$payroll->bulan.' telah ditransfer dan slip telah dikirimkan ke email anda. *Pesan ini adalah pesan otomatis, jangan membalas atau mengirimkan pesan kembali. Terima kasih.*');
                }
                
                $response = [
                    'status'    => 200,
                    'message'   => 'Gaji berhasil dikirimkan!'
                ];

            } catch (\Exception $e) {
                $response = [
                    'status'  => 500,
                    'message' => "Data failed to save : ".$e->getMessage()
                ];
            }
        }else{
            $response = [
                'status'  => 500,
                'message' => "Data tidak ditemukan."
            ];
        }

        return response()->json($response);
    }

    public function history(Request $request){
        $data = Payroll::find(intval($request->id));

        if($data){

            $content = '<table class="table table-bordered table-striped table-condensed flip-content">
                        <thead>
                            <tr>
                                <th colspan="5">History Email</th>
                            </tr>
                            <tr>
                                <th>No</th>
                                <th>Tanggal Proses</th>
                                <th>Status</th>
                            </tr>
                        </thead><tbody>';
                    
            if($data->historyEmail()->exists()){
                foreach($data->historyEmail as $key => $row){
                    $content .= '
                        <tr>
                            <td class="text-center">'.($key + 1).'</td>
                            <td>'.date('d/m/y H:i:s',strtotime($row->date_process)).'</td>
                            <td>'.$row->status().'</td>
                        </tr>
                    ';
                }
            }else{
                $content .= '<tr><td colspan="3" class="text-center">History email tidak ditemukan.</td></tr>';
            }
            
            $content .= '</tbody></table>';

            $response = [
                'status'  => 200,
                'content' => $content,     
                'message' => "Data berhasil dilihat."
            ];
        }else{
            $response = [
                'status'  => 500,
                'message' => "Data tidak ditemukan."
            ];
        }

        return response()->json($response);
    }
}
