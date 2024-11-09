<?php

namespace App\Http\Controllers;

use App\Helpers\CustomHelper;
use App\Http\Controllers\Controller;
use App\Models\Project;
use GuzzleHttp\Psr7\Query;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Process;

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
            'pic_name',
            'pic_no',
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
                            ->orWhere('pic_name', 'like', "%$search%")
                            ->orWhere('pic_no', 'like', "%$search%")
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
                            ->orWhere('pic_name', 'like', "%$search%")
                            ->orWhere('pic_no', 'like', "%$search%")
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
                    $val->pic_name,
                    $val->pic_no,
                    number_format($val->cost,2,',','.'),
                    $val->termin,
                    $val->note,
                    $val->statusBadge(),
                    '
                        <a href="javascript:void(0);" class="btn btn-secondary btn-sm content-icon" onclick="recap(`'.CustomHelper::encrypt($val->code).'`)"><i class="fa fa-info-circle"></i></a>
                        <a href="javascript:void(0);" class="btn btn-success btn-sm content-icon" onclick="done(`'.CustomHelper::encrypt($val->code).'`)"><i class="fa fa-check-circle"></i></a>
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
                    $query = Project::where('code',CustomHelper::decrypt($request->temp))->first();
                    $query->user_id         = session('bo_id');
                    $query->code            = $request->code;
                    $query->name            = $request->name;    
                    $query->customer_id     = $request->customer_id;
                    $query->pic_name        = $request->pic_name;
                    $query->pic_no          = $request->pic_no;
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
                    $query->status          = '2';
                    $query->save();
                    CustomHelper::saveLog($query->getTable(),$query->id,'Update data proyek '.$query->code,'Pengguna '.session('bo_nama').' telah mengubah data proyek no '.$query->code);
                }else{
                    $query = Project::create([
                        'code'                  => Project::generateCode(),
                        'user_id'               => session('bo_id'),
                        'name'                  => $request->name,
                        'customer_id'           => $request->customer_id,
                        'pic_name'              => $request->pic_name,
                        'pic_no'                => $request->pic_no,
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
                        'status'                => '2'
                    ]);
                    CustomHelper::saveLog($query->getTable(),$query->id,'Tambah baru data proyek '.$query->code,'Pengguna '.session('bo_nama').' telah manambahkan baru data proyek no '.$query->code);
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

    public function done(Request $request){
        $data = Project::where('code',CustomHelper::decrypt($request->code))->where('status','2')->first();
        if($data){
            
            if($data->offeringLetter()->exists()){
                $data->offeringLetter()->where('status','1')->update([
                    'status'    => '4',
                ]);
                $data->offeringLetter()->where('status','2')->update([
                    'status'    => '3',
                ]);
            }

            if($data->letterAgreement()->exists()){
                $data->letterAgreement()->where('status','1')->update([
                    'status'    => '4',
                ]);
                $data->letterAgreement()->where('status','2')->update([
                    'status'    => '3',
                ]);
            }

            if($data->invoice()->exists()){
                $data->invoice()->where('status','1')->update([
                    'status'    => '4',
                ]);
                $data->invoice()->where('status','2')->update([
                    'status'    => '3',
                ]);
            }

            if($data->surveyResult()->exists()){
                $data->surveyResult()->where('status','1')->update([
                    'status'    => '4',
                ]);
                $data->surveyResult()->where('status','2')->update([
                    'status'    => '3',
                ]);
            }

            if($data->surveyItem()->exists()){
                $data->surveyItem()->where('status','1')->update([
                    'status'    => '4',
                ]);
                $data->surveyItem()->where('status','2')->update([
                    'status'    => '3',
                ]);
            }

            if($data->surveyDocumentation()->exists()){
                $data->surveyDocumentation()->where('status','1')->update([
                    'status'    => '4',
                ]);
                $data->surveyDocumentation()->where('status','2')->update([
                    'status'    => '3',
                ]);
            }

            if($data->documentation()->exists()){
                $data->documentation()->where('status','1')->update([
                    'status'    => '4',
                ]);
                $data->documentation()->where('status','2')->update([
                    'status'    => '3',
                ]);
            }

            if($data->andalalin()->exists()){
                $data->andalalin()->where('status','1')->update([
                    'status'    => '4',
                ]);
                $data->andalalin()->where('status','2')->update([
                    'status'    => '3',
                ]);
            }

            if($data->hearing()->exists()){
                $data->hearing()->where('status','1')->update([
                    'status'    => '4',
                ]);
                $data->hearing()->where('status','2')->update([
                    'status'    => '3',
                ]);
            }

            if($data->revision()->exists()){
                $data->revision()->where('status','1')->update([
                    'status'    => '4',
                ]);
                $data->revision()->where('status','2')->update([
                    'status'    => '3',
                ]);
            }

            $data->update([
                'status'    => '3'
            ]);

            CustomHelper::saveLog($data->getTable(),$data->id,'Penutupan data proyek '.$data->code,'Pengguna '.session('bo_nama').' telah menutup data proyek no '.$data->code);

            $response = [
                'status'    => 200,
                'data'      => $data,
            ];
        }else{
            $response = [
                'status'  => 500,
                'message' => 'Data tidak ditemukan atau diluar perubahan.'
            ];
        }

        return response()->json($response);
    }

    public function recap(Request $request){
        $data = Project::where('code',CustomHelper::decrypt($request->code))->first();
        if($data){

            $html = '';

            #penawaran
            $html = '<table class="table table-responsive-md">
                    <thead>
                        <tr>
                            <th colspan="6"><strong>Surat Penawaran</strong></th>
                        </tr>
                        <tr>
                            <th><strong>#</strong></th>
                            <th><strong>Dokumen</strong></th>
                            <th><strong>Pengguna</strong></th>
                            <th><strong>Tgl.Dokumen</strong></th>
                            <th><strong>Status</strong></th>
                            <th><strong>Catatan</strong></th>
                        </tr>
                    </thead><tbody>';

            if($data->offeringLetter()->exists()){
                foreach($data->offeringLetter as $key => $row){
                    $html .= '<tr>
                        <td class="text-center">'.($key+1).'</td>
                        <td>'.$row->code.'</td>
                        <td>'.$row->user->nama.'</td>
                        <td>'.date('d/m/Y',strtotime($row->post_date)).'</td>
                        <td>'.$row->statusBadge().'</td>
                        <td>'.$row->note.'</td>
                    </tr>';
                }
            }else{
                $html .= '<tr>
                    <td class="text-center" colspan="6">Data tidak ditemukan.</td>
                </tr>';
            }

            $html .= '</tbody></table>';

            #spk

            $html .= '<table class="table table-responsive-md mt-2">
                    <thead>
                        <tr>
                            <th colspan="6"><strong>SPK</strong></th>
                        </tr>
                        <tr>
                            <th><strong>#</strong></th>
                            <th><strong>Dokumen</strong></th>
                            <th><strong>Pengguna</strong></th>
                            <th><strong>Tgl.Dokumen</strong></th>
                            <th><strong>Status</strong></th>
                            <th><strong>Catatan</strong></th>
                        </tr>
                    </thead><tbody>';

            if($data->letterAgreement()->exists()){
                foreach($data->letterAgreement as $key => $row){
                    $html .= '<tr>
                        <td class="text-center">'.($key+1).'</td>
                        <td>'.$row->code.'</td>
                        <td>'.$row->user->nama.'</td>
                        <td>'.date('d/m/Y',strtotime($row->post_date)).'</td>
                        <td>'.$row->statusBadge().'</td>
                        <td>'.$row->note.'</td>
                    </tr>';
                }
            }else{
                $html .= '<tr>
                    <td class="text-center" colspan="6">Data tidak ditemukan.</td>
                </tr>';
            }

            $html .= '</tbody></table>';

            #invoice

            $html .= '<table class="table table-responsive-md mt-2">
                    <thead>
                        <tr>
                            <th colspan="6"><strong>SPK</strong></th>
                        </tr>
                        <tr>
                            <th><strong>#</strong></th>
                            <th><strong>Dokumen</strong></th>
                            <th><strong>Pengguna</strong></th>
                            <th><strong>Tgl.Dokumen</strong></th>
                            <th><strong>Status</strong></th>
                            <th><strong>Catatan</strong></th>
                        </tr>
                    </thead><tbody>';

            if($data->invoice()->exists()){
                foreach($data->invoice as $key => $row){
                    $html .= '<tr>
                        <td class="text-center">'.($key+1).'</td>
                        <td>'.$row->code.'</td>
                        <td>'.$row->user->nama.'</td>
                        <td>'.date('d/m/Y',strtotime($row->post_date)).'</td>
                        <td>'.$row->statusBadge().'</td>
                        <td>'.$row->note.'</td>
                    </tr>';
                }
            }else{
                $html .= '<tr>
                    <td class="text-center" colspan="6">Data tidak ditemukan.</td>
                </tr>';
            }

            $html .= '</tbody></table>';

            #hasil survei

            $html .= '<table class="table table-responsive-md mt-2">
                    <thead>
                        <tr>
                            <th colspan="7"><strong>Hasil Survei</strong></th>
                        </tr>
                        <tr>
                            <th><strong>#</strong></th>
                            <th><strong>Dokumen</strong></th>
                            <th><strong>Pengguna</strong></th>
                            <th><strong>Tgl.Dokumen</strong></th>
                            <th><strong>Status</strong></th>
                            <th><strong>Catatan</strong></th>
                            <th><strong>Jumlah File</strong></th>
                        </tr>
                    </thead><tbody>';

            if($data->surveyResult()->exists()){
                foreach($data->surveyResult as $key => $row){
                    $html .= '<tr>
                        <td class="text-center">'.($key+1).'</td>
                        <td>'.$row->code.'</td>
                        <td>'.$row->user->nama.'</td>
                        <td>'.date('d/m/Y',strtotime($row->post_date)).'</td>
                        <td>'.$row->statusBadge().'</td>
                        <td>'.$row->note.'</td>
                        <td>'.$row->surveyResultDetail()->count().'</td>
                    </tr>';
                }
            }else{
                $html .= '<tr>
                    <td class="text-center" colspan="7">Data tidak ditemukan.</td>
                </tr>';
            }

            $html .= '</tbody></table>';

            #item survei

            $html .= '<table class="table table-responsive-md mt-2">
                    <thead>
                        <tr>
                            <th colspan="7"><strong>Item Survei</strong></th>
                        </tr>
                        <tr>
                            <th><strong>#</strong></th>
                            <th><strong>Dokumen</strong></th>
                            <th><strong>Pengguna</strong></th>
                            <th><strong>Tgl.Dokumen</strong></th>
                            <th><strong>Status</strong></th>
                            <th><strong>Catatan</strong></th>
                            <th><strong>Jumlah File</strong></th>
                        </tr>
                    </thead><tbody>';

            if($data->surveyItem()->exists()){
                foreach($data->surveyItem as $key => $row){
                    $html .= '<tr>
                        <td class="text-center">'.($key+1).'</td>
                        <td>'.$row->code.'</td>
                        <td>'.$row->user->nama.'</td>
                        <td>'.date('d/m/Y',strtotime($row->post_date)).'</td>
                        <td>'.$row->statusBadge().'</td>
                        <td>'.$row->note.'</td>
                        <td>'.$row->surveyItemDetail()->count().'</td>
                    </tr>';
                }
            }else{
                $html .= '<tr>
                    <td class="text-center" colspan="7">Data tidak ditemukan.</td>
                </tr>';
            }

            $html .= '</tbody></table>';

            #dokumentasi survei

            $html .= '<table class="table table-responsive-md mt-2">
                    <thead>
                        <tr>
                            <th colspan="7"><strong>Dokumentasi Survei</strong></th>
                        </tr>
                        <tr>
                            <th><strong>#</strong></th>
                            <th><strong>Dokumen</strong></th>
                            <th><strong>Pengguna</strong></th>
                            <th><strong>Tgl.Dokumen</strong></th>
                            <th><strong>Status</strong></th>
                            <th><strong>Catatan</strong></th>
                            <th><strong>Jumlah File</strong></th>
                        </tr>
                    </thead><tbody>';

            if($data->surveyDocumentation()->exists()){
                foreach($data->surveyDocumentation as $key => $row){
                    $html .= '<tr>
                        <td class="text-center">'.($key+1).'</td>
                        <td>'.$row->code.'</td>
                        <td>'.$row->user->nama.'</td>
                        <td>'.date('d/m/Y',strtotime($row->post_date)).'</td>
                        <td>'.$row->statusBadge().'</td>
                        <td>'.$row->note.'</td>
                        <td>'.$row->surveyDocumentationDetail()->count().'</td>
                    </tr>';
                }
            }else{
                $html .= '<tr>
                    <td class="text-center" colspan="7">Data tidak ditemukan.</td>
                </tr>';
            }

            $html .= '</tbody></table>';

            #kelangkapan dokumen

            $html .= '<table class="table table-responsive-md mt-2">
                    <thead>
                        <tr>
                            <th colspan="7"><strong>Kelengkapan Dokumen</strong></th>
                        </tr>
                        <tr>
                            <th><strong>#</strong></th>
                            <th><strong>Dokumen</strong></th>
                            <th><strong>Pengguna</strong></th>
                            <th><strong>Tgl.Dokumen</strong></th>
                            <th><strong>Status</strong></th>
                            <th><strong>Catatan</strong></th>
                            <th><strong>Jumlah File</strong></th>
                        </tr>
                    </thead><tbody>';

            if($data->documentation()->exists()){
                foreach($data->documentation as $key => $row){
                    $html .= '<tr>
                        <td class="text-center">'.($key+1).'</td>
                        <td>'.$row->code.'</td>
                        <td>'.$row->user->nama.'</td>
                        <td>'.date('d/m/Y',strtotime($row->post_date)).'</td>
                        <td>'.$row->statusBadge().'</td>
                        <td>'.$row->note.'</td>
                        <td>'.$row->documentationDetail()->count().'</td>
                    </tr>';
                }
            }else{
                $html .= '<tr>
                    <td class="text-center" colspan="7">Data tidak ditemukan.</td>
                </tr>';
            }

            $html .= '</tbody></table>';

            #Andalalin

            $html .= '<table class="table table-responsive-md mt-2">
                    <thead>
                        <tr>
                            <th colspan="7"><strong>Dokumen Andalalin</strong></th>
                        </tr>
                        <tr>
                            <th><strong>#</strong></th>
                            <th><strong>Dokumen</strong></th>
                            <th><strong>Pengguna</strong></th>
                            <th><strong>Tgl.Dokumen</strong></th>
                            <th><strong>Status</strong></th>
                            <th><strong>Catatan</strong></th>
                            <th><strong>Jumlah File</strong></th>
                        </tr>
                    </thead><tbody>';

            if($data->andalalin()->exists()){
                foreach($data->andalalin as $key => $row){
                    $html .= '<tr>
                        <td class="text-center">'.($key+1).'</td>
                        <td>'.$row->code.'</td>
                        <td>'.$row->user->nama.'</td>
                        <td>'.date('d/m/Y',strtotime($row->post_date)).'</td>
                        <td>'.$row->statusBadge().'</td>
                        <td>'.$row->note.'</td>
                        <td>'.$row->andalalinDetail()->count().'</td>
                    </tr>';
                }
            }else{
                $html .= '<tr>
                    <td class="text-center" colspan="7">Data tidak ditemukan.</td>
                </tr>';
            }

            $html .= '</tbody></table>';

            #sidang

            $html .= '<table class="table table-responsive-md mt-2">
                    <thead>
                        <tr>
                            <th colspan="6"><strong>Sidang</strong></th>
                        </tr>
                        <tr>
                            <th><strong>#</strong></th>
                            <th><strong>Dokumen</strong></th>
                            <th><strong>Pengguna</strong></th>
                            <th><strong>Tgl.Dokumen</strong></th>
                            <th><strong>Status</strong></th>
                            <th><strong>Catatan</strong></th>
                        </tr>
                    </thead><tbody>';

            if($data->hearing()->exists()){
                foreach($data->hearing as $key => $row){
                    $html .= '<tr>
                        <td class="text-center">'.($key+1).'</td>
                        <td>'.$row->code.'</td>
                        <td>'.$row->user->nama.'</td>
                        <td>'.date('d/m/Y',strtotime($row->post_date)).'</td>
                        <td>'.$row->statusBadge().'</td>
                        <td>'.$row->note.'</td>
                    </tr>';
                }
            }else{
                $html .= '<tr>
                    <td class="text-center" colspan="6">Data tidak ditemukan.</td>
                </tr>';
            }

            $html .= '</tbody></table>';

            #revisi

            $html .= '<table class="table table-responsive-md mt-2">
                    <thead>
                        <tr>
                            <th colspan="7"><strong>Revisi</strong></th>
                        </tr>
                        <tr>
                            <th><strong>#</strong></th>
                            <th><strong>Dokumen</strong></th>
                            <th><strong>Pengguna</strong></th>
                            <th><strong>Tgl.Dokumen</strong></th>
                            <th><strong>Status</strong></th>
                            <th><strong>Catatan</strong></th>
                            <th><strong>Jumlah File</strong></th>
                        </tr>
                    </thead><tbody>';

            if($data->revision()->exists()){
                foreach($data->revision as $key => $row){
                    $html .= '<tr>
                        <td class="text-center">'.($key+1).'</td>
                        <td>'.$row->code.'</td>
                        <td>'.$row->user->nama.'</td>
                        <td>'.date('d/m/Y',strtotime($row->post_date)).'</td>
                        <td>'.$row->statusBadge().'</td>
                        <td>'.$row->note.'</td>
                        <td>'.$row->revisionDetail()->count().'</td>
                    </tr>';
                }
            }else{
                $html .= '<tr>
                    <td class="text-center" colspan="7">Data tidak ditemukan.</td>
                </tr>';
            }

            $html .= '</tbody></table>';

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
}
