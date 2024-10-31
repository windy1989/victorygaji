<?php

namespace App\Http\Controllers;

use App\Helpers\CustomHelper;
use App\Http\Controllers\Controller;
use App\Models\Customer;
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

class OfferingLetterController extends Controller
{
    public function index()
    {
        $data = [
            'title'         => 'Surat Penawaran',
            'content'       => 'offering_letter',
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
            'to_name',
            'type_building',
            'location_building',
            'type_road',
            'is_pnbp',
            'is_include_tax',
            'note',
            'status',
        ];

        $start  = $request->start;
        $length = $request->length;
        $order  = $column[$request->input('order.0.column')];
        $dir    = $request->input('order.0.dir');
        $search = $request->input('search.value');

        $total_data = OfferingLetter::count();
        
        $query_data = OfferingLetter::where(function($query) use ($search, $request) {
                if($search) {
                    $query->where(function($query) use ($search, $request) {
                        $query->where('code', 'like', "%$search%")
                            ->orWhere('to_name', 'like', "%$search%")
                            ->orWhere('type_building', 'like', "%$search%")
                            ->orWhere('location_building', 'like', "%$search%")
                            ->orWhere('type_road', 'like', "%$search%")
                            ->orWhere('note', 'like', "%$search%")
							->orWhereHas('project',function($query) use ($search){
                                $query->whereHas('customer', function($query) use ($search){
                                    $query->where('code','like',"%$search%")
                                        ->orWhere('name','like',"%$search%");
                                })->orWhere('code', 'like', "%$search%");
                            });
                    });
                }
            })
            ->offset($start)
            ->limit($length)
            ->orderBy($order, $dir)
            ->get();

        $total_filtered = OfferingLetter::where(function($query) use ($search, $request) {
                if($search) {
                    $query->where(function($query) use ($search, $request) {
                        $query->where('code', 'like', "%$search%")
                            ->orWhere('to_name', 'like', "%$search%")
                            ->orWhere('type_building', 'like', "%$search%")
                            ->orWhere('location_building', 'like', "%$search%")
                            ->orWhere('type_road', 'like', "%$search%")
                            ->orWhere('note', 'like', "%$search%")
							->orWhereHas('project',function($query) use ($search){
                                $query->whereHas('customer', function($query) use ($search){
                                    $query->where('code','like',"%$search%")
                                        ->orWhere('name','like',"%$search%");
                                })->orWhere('code', 'like', "%$search%");
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
                    $val->to_name,
                    $val->type_building,
                    $val->location_building,
                    $val->type_road,
                    $val->isPnbp(),
                    $val->isIncludeTax(),
                    $val->note,
                    $val->statusBadge(),
                    '
                        <a href="javascript:void(0);" class="btn btn-secondary btn-sm content-icon" onclick="detail(`'.CustomHelper::encrypt($val->code).'`)"><i class="fa fa-info-circle"></i></a>
                        <a href="'.env('APP_URL').'/surat_penawaran/print/'.CustomHelper::encrypt($val->code).'" class="btn btn-info btn-sm content-icon" data-toggle="tooltip" data-placement="top" title="Cetak Invoice"><i class="fa fa-print"></i></a>
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
                'code'		            => $request->temp ? ['required', Rule::unique('offering_letters', 'code')->ignore(CustomHelper::decrypt($request->temp),'code')] : 'required|unique:offering_letters,code',
                'to_name'               => 'required',
                'project_id'            => 'required',
                'post_date'             => 'required',
                'type_building'         => 'required',
                'location_building'     => 'required',
                'type_road'             => 'required',
            ], [
                'code.required'             => 'Kode tidak boleh kosong.',
                'code.unique'               => 'Kode telah dipakai.',
                'to_name.required'          => 'Ditujukan kepada tidak boleh kosong.',
                'project_id.required'       => 'Project tidak boleh kosong.',
                'post_date.required'        => 'Tgl. post tidak boleh kosong.',
                'type_building.required'    => 'Tipe Bangunan tidak boleh kosong.',
                'location_building.required'=> 'Lokasi gedung tidak boleh kosong.',
                'type_road.required'        => 'Tipe Jalan tidak boleh kosong.',
            ]);

            if($validation->fails()) {
                $response = [
                    'status' => 422,
                    'error'  => $validation->errors()
                ];
            } else {
                if($request->temp){
                    $query = OfferingLetter::where('code',CustomHelper::decrypt($request->temp))->first();

                    if($query->status == '3'){
                        return response()->json([
                            'status'    => 500,
                            'message'   => 'Ups. Surat Penawaran telah SELESAI, anda tidak bisa melakukan perubahan.'
                        ]);
                    }

                    $query->user_id             = session('bo_id');
                    $query->code                = $request->code;
                    $query->project_id          = $request->project_id;
                    $query->post_date           = $request->post_date;
                    $query->to_name             = $request->to_name;
                    $query->type_building       = $request->type_building;
                    $query->location_building   = $request->location_building;
                    $query->type_road           = $request->type_road;
                    $query->note                = $request->note;
                    $query->status              = '3';
                    $query->save();
                    CustomHelper::saveLog($query->getTable(),$query->id,'Update data surat penawaran '.$query->code,'Pengguna '.session('bo_nama').' telah mengubah data surat penawaran no '.$query->code);
                }else{
                    $query = OfferingLetter::create([
                        'user_id'               => session('bo_id'),
                        'code'                  => $request->code,
                        'project_id'            => $request->project_id,
                        'post_date'             => $request->post_date,
                        'to_name'               => $request->to_name,
                        'type_building'         => $request->type_building,
                        'location_building'     => $request->location_building,
                        'type_road'             => $request->type_road,
                        'note'                  => $request->note,
                        'status'                => '3',
                    ]);
                    CustomHelper::saveLog($query->getTable(),$query->id,'Tambah baru data surat penawaran '.$query->code,'Pengguna '.session('bo_nama').' telah manambahkan baru data surat penawaran no '.$query->code);
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
        $data = OfferingLetter::where('code',CustomHelper::decrypt($request->code))->first();
        if($data){
            $data['project_code'] = $data->project->code.' - '.$data->project->name.' - '.$data->project->customer->name;
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
        $data = OfferingLetter::where('code',CustomHelper::decrypt($request->code))->first();
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
                                <th><strong>Catatan</strong></th>
                            </tr>
                        </thead><tbody>';

                foreach($data->approval()->orderBy('approve_level')->get() as $key => $row){
                    $html .= '<tr>
                        <td class="text-center">'.($key+1).'</td>
                        <td>'.$row->toUser->nama.'</td>
                        <td>'.$row->approve_level.'</td>
                        <td>'.($row->approve_date ? date('d/m/Y H:i:s',strtotime($row->approve_date)) : '-').'</td>
                        <td>'.$row->approveStatus().'</td>
                        <td>'.$row->approve_note.'</td>
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
        $query = OfferingLetter::where('code',CustomHelper::decrypt($request->code))->first();
        if($query){
            if($query->status == '1'){
                CustomHelper::saveLog($query->getTable(),$query->id,'Surat Penawaran nomor '.$query->code.' telah dihapus.','Pengguna '.session('bo_nama').' telah menghapus data Surat Penawaran no '.$query->code);

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
        $data = OfferingLetter::where('code',CustomHelper::decrypt($id))->first();
        if($data){

            $result = [
                'title'         => 'Surat Penawaran '.$data->code,
                'data'          => $data,
            ];
    
            $pdf = Pdf::loadView('pdf.offering_letter', $result);
            return $pdf->stream('offering_letter_'.$data->code.'.pdf');
            /* return $pdf->download('invoice.pdf'); */
        }else{
            abort(404);
        }
    }
}
