<?php

namespace App\Http\Controllers;

use App\Helpers\CustomHelper;
use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\LetterAgreement;
use App\Models\OfferingLetter;
use App\Models\Project;
use Barryvdh\DomPDF\Facade\Pdf;
use GuzzleHttp\Psr7\Query;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use Process;
use Illuminate\Support\Facades\Storage;

class LetterAgreementController extends Controller
{
    public function index()
    {
        $data = [
            'title'         => 'SPK',
            'content'       => 'letter_agreement',
        ];

        return view('layouts.index', ['data' => $data]);
    }

    public function datatable(Request $request){
        $column = [
            'id',
            'code',
            'user_id',
            'project_id',
			'post_date',
            'name',
            'address',
            'position',
            'phone',
            'name_ref',
            'type_building',
            'name_builder',
            'persil_location',
            'land_area',
            'building_area',
            'subdistrict',
            'district',
            'city',
            'province',
            'road_status',
            'nominal_1',
            'nominal_2',
            'nominal_3',
            'estimate_date_start',
            'estimate_date_finish',
            'note',
            'status',
        ];

        $start  = $request->start;
        $length = $request->length;
        $order  = $column[$request->input('order.0.column')];
        $dir    = $request->input('order.0.dir');
        $search = $request->input('search.value');

        $total_data = LetterAgreement::count();
        
        $query_data = LetterAgreement::where(function($query) use ($search, $request) {
                if($search) {
                    $query->where(function($query) use ($search, $request) {
                        $query->where('code', 'like', "%$search%")
                            ->orWhere('name', 'like', "%$search%")
                            ->orWhere('address', 'like', "%$search%")
                            ->orWhere('position', 'like', "%$search%")
                            ->orWhere('phone', 'like', "%$search%")
                            ->orWhere('note', 'like', "%$search%")
							->orWhereHas('project',function($query) use ($search){
                                $query->whereHas('customer', function($query) use ($search){
                                    $query->where('code','like',"%$search%")
                                        ->orWhere('name','like',"%$search%");
                                });
                            });
                    });
                }
            })
            ->offset($start)
            ->limit($length)
            ->orderBy($order, $dir)
            ->get();

        $total_filtered = LetterAgreement::where(function($query) use ($search, $request) {
                if($search) {
                    $query->where(function($query) use ($search, $request) {
                        $query->where('code', 'like', "%$search%")
                            ->orWhere('name', 'like', "%$search%")
                            ->orWhere('address', 'like', "%$search%")
                            ->orWhere('position', 'like', "%$search%")
                            ->orWhere('phone', 'like', "%$search%")
                            ->orWhere('note', 'like', "%$search%")
                            ->orWhereHas('project',function($query) use ($search){
                                $query->whereHas('customer', function($query) use ($search){
                                    $query->where('code','like',"%$search%")
                                        ->orWhere('name','like',"%$search%");
                                });
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
                    $val->project->project_no.' - '.$val->project->customer->name,
                    date('d/m/Y',strtotime($val->post_date)),
                    $val->name,
                    $val->address,
                    $val->position,
                    $val->phone,
                    $val->name_ref,
                    $val->type_building,
                    $val->name_builder,
                    $val->persil_location,
                    $val->land_area,
                    $val->building_area,
                    $val->subdistrict,
                    $val->district,
                    $val->city,
                    $val->province,
                    $val->road_status,
                    number_format($val->nominal_1,2,',','.'),
                    number_format($val->nominal_2,2,',','.'),
                    number_format($val->nominal_3,2,',','.'),
                    date('d/m/Y',strtotime($val->estimate_date_start)),
                    date('d/m/Y',strtotime($val->estimate_date_finish)),
                    $val->note,
                    $val->statusBadge(),
                    '
                        <!-- <a href="javascript:void(0);" class="btn btn-secondary btn-sm content-icon" onclick="detail(`'.CustomHelper::encrypt($val->code).'`)"><i class="fa fa-info-circle"></i></a> -->
                        <a href="'.env('APP_URL').'/spk/print/'.CustomHelper::encrypt($val->code).'" class="btn btn-info btn-sm content-icon" data-toggle="tooltip" data-placement="top" title="Cetak Invoice"><i class="fa fa-print"></i></a>
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
                'code'		            => $request->temp ? ['required', Rule::unique('letter_agreements', 'code')->ignore($request->temp)] : 'required|unique:letter_agreements,code',
                'name'                  => 'required',
                'project_id'            => 'required',
                'post_date'             => 'required',
                'address'               => 'required',
                'position'              => 'required',
                'phone'                 => 'required',
                'name_ref'              => 'required',
                'type_building'         => 'required',
                'name_builder'          => 'required',
                'persil_location'       => 'required',
                'land_area'             => 'required',
                'building_area'         => 'required',
                'subdistrict'           => 'required',
                'district'              => 'required',
                'city'                  => 'required',
                'province'              => 'required',
                'road_status'           => 'required',
                'nominal_1'             => 'required',
                'nominal_2'             => 'required',
                'nominal_3'             => 'required',
                'estimate_date_start'   => 'required',
                'estimate_date_finish'  => 'required',
            ], [
                'code.required'                 => 'Kode tidak boleh kosong.',
                'code.unique'                   => 'Kode telah dipakai.',
                'name.required'                 => 'Nama pihak 1 tidak boleh kosong.',
                'project_id.required'           => 'Project tidak boleh kosong.',
                'post_date.required'            => 'Tgl. post tidak boleh kosong.',
                'type_building.required'        => 'Tipe Bangunan tidak boleh kosong.',
                'name_builder.required'         => 'Nama pembangun.',
                'persil_location.required'      => 'Lokasi persil.',
                'address.required'              => 'Alamat tidak boleh kosong.',
                'position.required'             => 'Posisi/Jabatan pihak 1 tidak boleh kosong.',
                'phone.required'                => 'Telepon pihak 1 tidak boleh kosong.',
                'name_ref.required'             => 'Atas nama perwakilan tidak boleh kosong (Jika diwakilkan).',
                'land_area.required'            => 'Luas Lahan tidak boleh kosong.',
                'building_area.required'        => 'Luas Bangunan tidak boleh kosong.',
                'subdistrict.required'          => 'Desa/Kelurahan tidak boleh kosong.',
                'district.required'             => 'Kecamatan tidak boleh kosong.',
                'city.required'                 => 'Kabupaten/Kota tidak boleh kosong.',
                'province.required'             => 'Provinsi tidak boleh kosong.',
                'road_status.required'          => 'Status jalan tidak boleh kosong.',
                'nominal_1.required'            => 'Nominal termin 1 tidak boleh kosong.',
                'nominal_2.required'            => 'Nominal termin 2 tidak boleh kosong.',
                'nominal_3.required'            => 'Nominal termin 3 tidak boleh kosong.',
                'estimate_date_start.required'  => 'Estimasi tanggal mulai pengerjaan tidak boleh kosong.',
                'estimate_date_finish.required' => 'Estimasi tanggal selesai pengerjaan tidak boleh kosong.',
            ]);

            if($validation->fails()) {
                $response = [
                    'status' => 422,
                    'error'  => $validation->errors()
                ];
            } else {
                if($request->temp){
                    $query = LetterAgreement::where('code',CustomHelper::decrypt($request->temp))->first();

                    if($query->status == '3'){
                        return response()->json([
                            'status'    => 500,
                            'message'   => 'Ups. Surat SPK telah SELESAI, anda tidak bisa melakukan perubahan.'
                        ]);
                    }

                    $query->user_id             = session('bo_id');
                    $query->code                = $request->code;
                    $query->project_id          = $request->project_id;
                    $query->post_date           = $request->post_date;
                    $query->name                = $request->name;
                    $query->type_building       = $request->type_building;
                    $query->name_builder        = $request->name_builder;
                    $query->type_road           = $request->type_road;
                    $query->address             = $request->address;
                    $query->phone               = $request->phone;
                    $query->position            = $request->position;
                    $query->name_ref            = $request->name_ref;
                    $query->persil_location     = $request->persil_location;
                    $query->land_area           = str_replace(',','.',str_replace('.','',$request->land_area));
                    $query->building_area       = str_replace(',','.',str_replace('.','',$request->building_area));
                    $query->subdistrict         = $request->subdistrict;
                    $query->district            = $request->district;
                    $query->city                = $request->city;
                    $query->province            = $request->province;
                    $query->road_status         = $request->road_status;
                    $query->nominal_1           = str_replace(',','.',str_replace('.','',$request->nominal_1));
                    $query->nominal_2           = str_replace(',','.',str_replace('.','',$request->nominal_2));
                    $query->nominal_3           = str_replace(',','.',str_replace('.','',$request->nominal_3));
                    $query->estimate_date_start = $request->estimate_date_start;
                    $query->estimate_date_finish= $request->estimate_date_finish;
                    $query->note                = $request->note;
                    $query->status              = '3';
                    $query->save();
                    CustomHelper::saveLog($query->getTable(),$query->id,'Update data surat spk '.$query->code,'Pengguna '.session('bo_name').' telah mengubah data surat spk no '.$query->code);
                }else{
                    $query = LetterAgreement::create([
                        'user_id'                   => session('bo_id'),
                        'code'                      => $request->code,
                        'project_id'                => $request->project_id,
                        'post_date'                 => $request->post_date,
                        'name'                      => $request->name,
                        'type_building'             => $request->type_building,
                        'name_builder'              => $request->name_builder,
                        'type_road'                 => $request->type_road,
                        'address'                   => $request->address,
                        'position'                  => $request->position,
                        'phone'                     => $request->phone,
                        'name_ref'                  => $request->name_ref,
                        'persil_location'           => $request->persil_location,
                        'land_area'                 => str_replace(',','.',str_replace('.','',$request->land_area)),
                        'building_area'             => str_replace(',','.',str_replace('.','',$request->building_area)),
                        'subdistrict'               => $request->subdistrict,
                        'district'                  => $request->district,
                        'city'                      => $request->city,
                        'province'                  => $request->province,
                        'road_status'               => $request->road_status,
                        'nominal_1'                 => str_replace(',','.',str_replace('.','',$request->nominal_1)),
                        'nominal_2'                 => str_replace(',','.',str_replace('.','',$request->nominal_2)),
                        'nominal_3'                 => str_replace(',','.',str_replace('.','',$request->nominal_3)),
                        'estimate_date_start'       => $request->estimate_date_start,
                        'estimate_date_finish'      => $request->estimate_date_finish,
                        'note'                      => $request->note,
                        'status'                    => '3',
                    ]);
                    CustomHelper::saveLog($query->getTable(),$query->id,'Tambah baru data surat spk '.$query->code,'Pengguna '.session('bo_name').' telah manambahkan baru data surat spk no '.$query->code);
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
        $data = LetterAgreement::where('code',CustomHelper::decrypt($request->code))->first();
        if($data){
            $data['project_code'] = $data->project->code.' - '.$data->project->name.' - '.$data->project->customer->name;
            $data['land_area'] = number_format($data->land_area,2,',','.');
            $data['building_area'] = number_format($data->building_area,2,',','.');
            $data['nominal_1'] = number_format($data->nominal_1,2,',','.');
            $data['nominal_2'] = number_format($data->nominal_2,2,',','.');
            $data['nominal_3'] = number_format($data->nominal_3,2,',','.');
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

    public function detail(Request $request){
        $data = LetterAgreement::where('code',CustomHelper::decrypt($request->code))->first();
        if($data){

            $html = '';

            if($data->approval()->exists()){
                $html = '<table class="table table-responsive-md">
                        <thead>
                            <tr>
                                <th><strong>#</strong></th>
                                <th><strong>APPROVER</strong></th>
                                <th><strong>LEVEL</strong></th>
                                <th><strong>TGL.APPROVE</strong></th>
                                <th><strong>STATUS</strong></th>
                            </tr>
                        </thead><tbody>';

                foreach($data->approval()->orderBy('approve_level')->get() as $key => $row){
                    $html .= '<tr>
                        <td class="text-center">'.($key+1).'</td>
                        <td>'.$row->toUser->nama.'</td>
                        <td>'.$row->approve_level.'</td>
                        <td>'.($row->approve_date ? date('d/m/Y H:i:s',strtotime($row->approve_date)) : '-').'</td>
                        <td>'.$row->approveStatus().'</td>
                    </tr>';
                }

                $html .= '</tbody></table>';
            }

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

    public function destroy(Request $request){
        $query = LetterAgreement::where('code',CustomHelper::decrypt($request->code))->first();
        if($query){
            if($query->status == '1'){
                CustomHelper::saveLog($query->getTable(),$query->id,'Surat SPK nomor '.$query->code.' telah dihapus.','Pengguna '.session('bo_name').' telah menghapus data Surat SPK no '.$query->code);

                $query->approval()->delete();
                $query->delete();

                $response = [
                    'status'    => 200,
                    'message'   => 'Data berhasil dihapus.',
                ];
            }else{
                $response = [
                    'status'    => 500,
                    'message'   => 'Hanya dokumen MENUNGGU yang bisa dihapus.',
                ];
            }
        }else{
            $response = [
                'status'  => 500,
                'message' => 'Data tidak ditemukan.'
            ];
        }

        return response()->json($response);
    }

    public function print(Request $request,$id){
        $data = LetterAgreement::where('code',CustomHelper::decrypt($id))->first();
        if($data){

            $result = [
                'title'         => 'Surat Penawaran '.$data->code,
                'data'          => $data,
            ];
    
            $pdf = Pdf::loadView('pdf.letter_agreement', $result);

            $font = $pdf->getFontMetrics()->get_font("helvetica", "italic");
            $pdf->getCanvas()->page_text(250, 750, "{PAGE_NUM} of {PAGE_COUNT}", $font, 10, array(0,0,0));

            return $pdf->stream('letter_agreement_'.$data->code.'.pdf');
            /* return $pdf->download('invoice.pdf'); */
        }else{
            abort(404);
        }
    }
}
