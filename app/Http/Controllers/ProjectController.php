<?php

namespace App\Http\Controllers;

use App\Helpers\CustomHelper;
use App\Http\Controllers\Controller;
use App\Imports\ImportPayroll;
use App\Jobs\QueueMail;
use App\Models\Payroll;
use App\Models\Project;
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
            'code',
            'user_id',
            'customer_id',
            'name',
            'project_no',
            'post_date',
			'location',
            'region_id',
            'project_type_id',
            'purpose_id',
            'purpose_note',
            'working_days',
            'start_date',
            'end_date',
            'andalalin_document_no',
            'power_letter_no',
            'cost',
            'termin',
            'note',
            'status',
        ];

        $start  = $request->start;
        $length = $request->length;
        $order  = $column[$request->input('order.0.column')];
        $dir    = $request->input('order.0.dir');
        $search = $request->input('search.value');

        $total_data = Project::count();
        
        $query_data = Project::where(function($query) use ($search, $request) {
                if($search) {
                    $query->where(function($query) use ($search, $request) {
                        $query->where('code', 'like', "%$search%")
                            ->orWhere('name', 'like', "%$search%")
                            ->orWhere('project_no', 'like', "%$search%")
                            ->orWhere('location', 'like', "%$search%")
							->orWhereHas('customer', function($query) use ($search){
								$query->where('code','like',"%$search%")
                                    ->orWhere('name','like',"%$search%");
							});
                    });
                }
            })
            ->offset($start)
            ->limit($length)
            ->orderBy($order, $dir)
            ->get();

        $total_filtered = Project::where(function($query) use ($search, $request) {
                if($search) {
                    $query->where(function($query) use ($search, $request) {
                        $query->where('code', 'like', "%$search%")
                            ->orWhere('name', 'like', "%$search%")
                            ->orWhere('project_no', 'like', "%$search%")
                            ->orWhere('location', 'like', "%$search%")
							->orWhereHas('customer', function($query) use ($search){
								$query->where('code','like',"%$search%")
                                    ->orWhere('name','like',"%$search%");
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
                    $val->customer->name,
                    $val->name,
                    $val->project_no,
                    date('d/m/Y',strtotime($val->post_date)),
                    $val->location,
                    $val->region->name,
                    $val->projectType->name,
                    $val->purpose->name,
                    $val->purpose_note,
                    $val->working_days,
                    date('d/m/Y',strtotime($val->start_date)),
                    date('d/m/Y',strtotime($val->end_date)),
                    $val->andalalin_document_no,
                    $val->power_letter_no,
                    number_format($val->cost,2,',','.'),
                    $val->termin,
                    $val->note,
                    $val->status(),
                    '
                        <a href="javascript:void(0);" class="btn btn-warning btn-sm content-icon" onclick="edit(`'.CustomHelper::encrypt($val->code).'`)"><i class="fa fa-edit"></i></a>
                        <a href="javascript:void(0);" class="btn btn-danger btn-sm content-icon" onclick="destroy(`'.CustomHelper::encrypt($val->code).'`)"><i class="fa fa-trash"></i></a>
                        <a href="javascript:void(0);" class="btn btn-primary btn-sm content-icon" onclick="void(`'.CustomHelper::encrypt($val->code).'`)"><i class="fa fa-times"></i></a>
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
