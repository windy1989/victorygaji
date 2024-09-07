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
                    $val->receipt_code,
                    $val->user->nama,
                    $val->receive_from,
                    $val->project->project_no,
                    $val->bank->no.' - '.$val->bank->bank,
                    date('d/m/Y',strtotime($val->post_date)),
                    date('d/m/Y',strtotime($val->pay_date)),
                    number_format($val->nominal,2,',','.'),
                    $val->termin_no,
                    $val->statusBadge(),
                    '
                        <a href="javascript:void(0);" class="btn btn-secondary btn-sm content-icon" onclick="recap(`'.CustomHelper::encrypt($val->code).'`)"><i class="fa fa-info-circle"></i></a>
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
                'name'                  => 'required',
                'customer_id'           => 'required',
                'project_no'            => 'required',
                'post_date'             => 'required',
                'location'              => 'required',
                'region_id'             => 'required',
                'project_type_id'       => 'required',
                'purpose_id'            => 'required',
                'working_days'          => 'required',
                'start_date'            => 'required',
                'end_date'              => 'required',
                'andalalin_document_no' => 'required',
                'power_letter_no'       => 'required',
                'cost'                  => 'required',
                'termin'                => 'required',
            ], [
                'name.required'                     => 'Nama tidak boleh kosong.',
                'customer_id.required'              => 'Customer tidak boleh kosong.',
                'project_no.required'               => 'Proyek tidak boleh kosong.',
                'post_date.required'                => 'Tgl.Post tidak boleh kosong.',
                'location.required'                 => 'Lokasi/Alamat tidak boleh kosong.',
                'region_id.required'                => 'Kota tidak boleh kosong.',
                'project_type_id.required'          => 'Tipe Proyek tidak boleh kosong.',
                'purpose_id.required'               => 'Peruntukan tidak boleh kosong.',
                'working_days.required'             => 'Lama Pengerjaan tidak boleh kosong.',
                'start_date.required'               => 'Tgl.Mulai Pengerjaan tidak boleh kosong.',
                'end_date.required'                 => 'Tgl.Selesai Pengerjaan tidak boleh kosong.',
                'andalalin_document_no.required'    => 'Nomor andalalin tidak boleh kosong.',
                'power_letter_no.required'          => 'Nomor surat kuasa tidak boleh kosong.',
                'cost.required'                     => 'Biaya tidak boleh kosong.',
                'termin.required'                   => 'Termin tidak boleh kosong.',
            ]);

            if($validation->fails()) {
                $response = [
                    'status' => 422,
                    'error'  => $validation->errors()
                ];
            } else {
                if($request->temp){
                    $query = Project::find($request->temp);
                    $query->user_id         = session('bo_id');
                    $query->code            = $request->code;
                    $query->name            = $request->name;    
                    $query->customer_id     = $request->customer_id;
                    $query->project_no      = $request->project_no;
                    $query->post_date       = $request->post_date;
                    $query->location        = $request->location;
                    $query->region_id       = $request->region_id;
                    $query->project_type_id = $request->project_type_id;
                    $query->purpose_id      = $request->purpose_id;
                    $query->purpose_note    = $request->purpose_note;
                    $query->working_days    = $request->working_days;
                    $query->start_date      = $request->start_date;
                    $query->end_date        = $request->end_date;
                    $query->andalalin_document_no = $request->andalalin_document_no;
                    $query->power_letter_no = $request->power_letter_no;
                    $query->cost            = str_replace(',','.',str_replace('.','',$request->cost));
                    $query->termin          = $request->termin;
                    $query->note            = $request->note;
                    $query->status          = '1';
                    $query->save();
                    CustomHelper::saveLog($query->getTable(),$query->id,'Update data proyek '.$query->code,'Pengguna '.session('bo_name').' telah mengubah data proyek no '.$query->code);
                }else{
                    $query = Project::create([
                        'code'                  => Project::generateCode(),
                        'user_id'               => session('bo_id'),
                        'name'                  => $request->name,
                        'customer_id'           => $request->customer_id,
                        'project_no'            => $request->project_no,
                        'post_date'             => $request->post_date,
                        'location'              => $request->location,
                        'region_id'             => $request->region_id,
                        'project_type_id'       => $request->project_type_id,
                        'purpose_id'            => $request->purpose_id,
                        'purpose_note'          => $request->purpose_note,
                        'working_days'          => $request->working_days,
                        'start_date'            => $request->start_date,
                        'end_date'              => $request->end_date,
                        'andalalin_document_no' => $request->andalalin_document_no,
                        'power_letter_no'       => $request->power_letter_no,
                        'cost'                  => str_replace(',','.',str_replace('.','',$request->cost)),
                        'termin'                => $request->termin,
                        'note'                  => $request->note,
                        'status'                => '1'
                    ]);
                    CustomHelper::saveLog($query->getTable(),$query->id,'Tambah baru data proyek '.$query->code,'Pengguna '.session('bo_name').' telah manambahkan baru data proyek no '.$query->code);
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
