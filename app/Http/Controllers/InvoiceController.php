<?php

namespace App\Http\Controllers;

use App\Helpers\CustomHelper;
use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Project;
use GuzzleHttp\Psr7\Query;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use Process;

class InvoiceController extends Controller
{
    public function index()
    {
        $data = [
            'title'         => 'Proyek',
            'content'       => 'invoice',
        ];

        return view('layouts.index', ['data' => $data]);
    }

    public function datatable(Request $request){
        $column = [
            'id',
            'code',
            'receipt_code',
            'user_id',
            'receive_from',
            'project_id',
            'bank_id',
			'post_date',
            'pay_date',
            'nominal',
            'termin_no',
            'note',
            'status',
        ];

        $start  = $request->start;
        $length = $request->length;
        $order  = $column[$request->input('order.0.column')];
        $dir    = $request->input('order.0.dir');
        $search = $request->input('search.value');

        $total_data = Invoice::count();
        
        $query_data = Invoice::where(function($query) use ($search, $request) {
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

        $total_filtered = Invoice::where(function($query) use ($search, $request) {
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
                    $val->receipt_code ?? '-',
                    $val->user->nama,
                    $val->receive_from,
                    $val->project->project_no.' - '.$val->project->customer->name,
                    $val->bank->no.' - '.$val->bank->bank,
                    date('d/m/Y',strtotime($val->post_date)),
                    $val->pay_date ? date('d/m/Y',strtotime($val->pay_date)) : '-',
                    number_format($val->nominal,2,',','.'),
                    $val->termin_no,
                    $val->note,
                    $val->statusBadge(),
                    '
                        <a href="javascript:void(0);" class="btn btn-secondary btn-sm content-icon" onclick="recap(`'.CustomHelper::encrypt($val->code).'`)"><i class="fa fa-info-circle"></i></a>
                        <a href="javascript:void(0);" class="btn btn-success btn-sm content-icon" onclick="pay(`'.CustomHelper::encrypt($val->code).'`,`'.$val->code.'`)"><i class="fa fa-credit-card-alt"></i></a>
                        <a href="javascript:void(0);" class="btn btn-info btn-sm content-icon" onclick="print(`'.CustomHelper::encrypt($val->code).'`)" data-toggle="tooltip" data-placement="top" title="Tooltip on top"><i class="fa fa-print"></i></a>
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
        DB::beginTransaction();
        try {
            $validation = Validator::make($request->all(), [
                'code'		            => $request->temp ? ['required', Rule::unique('invoices', 'code')->ignore($request->temp)] : 'required|unique:invoices,code',
                'receive_from'          => 'required',
                'project_id'            => 'required',
                'bank_id'               => 'required',
                'post_date'             => 'required',
                'nominal'               => 'required',
                'termin_no'             => 'required',
            ], [
                'code.required'             => 'Kode tidak boleh kosong.',
                'code.unique'               => 'Kode telah dipakai.',
                'receive_from.required'     => 'Identitas pengirim tidak boleh kosong.',
                'project_id.required'       => 'Project tidak boleh kosong.',
                'bank_id.required'          => 'Bank tidak boleh kosong.',
                'post_date.required'        => 'Tgl. post tidak boleh kosong.',
                'nominal.required'          => 'Nominal tidak boleh kosong.',
                'termin_no.required'        => 'Termin pembayaran tidak boleh kosong.',
            ]);

            if($validation->fails()) {
                $response = [
                    'status' => 422,
                    'error'  => $validation->errors()
                ];
            } else {
                if($request->temp){
                    $query = Invoice::where('code',CustomHelper::decrypt($request->temp))->first();
                    $query->user_id         = session('bo_id');
                    $query->code            = $request->code;
                    $query->receive_from    = $request->receive_from;    
                    $query->project_id      = $request->project_id;
                    $query->bank_id         = $request->bank_id;
                    $query->post_date       = $request->post_date;
                    $query->nominal         = str_replace(',','.',str_replace('.','',$request->nominal));
                    $query->termin_no       = $request->termin_no;
                    $query->note            = $request->note;
                    $query->status          = '1';
                    $query->save();
                    CustomHelper::saveLog($query->getTable(),$query->id,'Update data invoice '.$query->code,'Pengguna '.session('bo_name').' telah mengubah data invoice no '.$query->code);
                }else{
                    $query = Invoice::create([
                        'user_id'         => session('bo_id'),
                        'code'            => $request->code,
                        'receive_from'    => $request->receive_from,
                        'project_id'      => $request->project_id,
                        'bank_id'         => $request->bank_id,
                        'post_date'       => $request->post_date,
                        'nominal'         => str_replace(',','.',str_replace('.','',$request->nominal)),
                        'termin_no'       => $request->termin_no,
                        'note'            => $request->note,
                        'status'          => '1',
                    ]);
                    CustomHelper::saveLog($query->getTable(),$query->id,'Tambah baru data invoice '.$query->code,'Pengguna '.session('bo_name').' telah manambahkan baru data invoice no '.$query->code);
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

    public function createReceipt(Request $request){
        DB::beginTransaction();
        try {
            $validation = Validator::make($request->all(), [
                'code_receipt'		    => 'required|unique:invoices,receipt_code',
                'pay_date'              => 'required',
                'tempReceipt'           => 'required',
                'fileReceipt'           => 'required|mimes:jpg,png,jpeg|max:1024',
            ], [
                'code_receipt.required'     => 'Kode kwitansi tidak boleh kosong.',
                'code_receipt.unique'       => 'Kode kwitansi telah dipakai.',
                'pay_date.required'         => 'Tgl. bayar tidak boleh kosong.',
                'tempReceipt.required'      => 'Bukti invoice tidak boleh kosong.',
                'fileReceipt.required'      => 'File bukti bayar tidak boleh kosong.',
                'fileReceipt.mimes'         => 'File bukti bayar harus berupa jpeg, png atau jpg.',
                'fileReceipt.max'           => 'File bukti bayar ukuran maksimal 1024Kb',
            ]);

            if($validation->fails()) {
                $response = [
                    'status' => 422,
                    'error'  => $validation->errors()
                ];
            } else {

                $query = Invoice::where('code',CustomHelper::decrypt($request->tempReceipt))->first();

                $imageName = Str::random(35).'.png';
                $path =storage_path('app/public/invoice/'.$imageName);
                $newFile = CustomHelper::compress($request->fileReceipt,$path,50);
                $basePath = storage_path('app');
                $desiredPath = explode($basePath.'/', $newFile)[1];

                $query->receipt_code    = $request->code_receipt;
                $query->pay_date        = $request->pay_date;
                $query->document        = $desiredPath;
                $query->status          = '1';
                $query->save();
                CustomHelper::saveLog($query->getTable(),$query->id,'Update pembayaran data invoice '.$query->code,'Pengguna '.session('bo_name').' telah mengubah data invoice no '.$query->code);
                
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
        $data = Project::where('code',CustomHelper::decrypt($request->code))->first();
        if($data){
            $data['customer_info'] = $data->customer->code.' - '.$data->customer->name;
            $data['region_info'] = $data->region->name;
            $data['project_type_info'] = $data->projectType->name;
            $data['purpose_info'] = $data->purpose->name;
            $data['cost'] = number_format($data->cost,2,',','.');
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
}
