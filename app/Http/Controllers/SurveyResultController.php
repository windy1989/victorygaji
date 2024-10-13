<?php

namespace App\Http\Controllers;

use App\Helpers\CustomHelper;
use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\LetterAgreement;
use App\Models\OfferingLetter;
use App\Models\Project;
use App\Models\SurveyResult;
use App\Models\SurveyResultDetail;
use Barryvdh\DomPDF\Facade\Pdf;
use GuzzleHttp\Psr7\Query;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use Process;
use Illuminate\Support\Facades\Storage;

class SurveyResultController extends Controller
{
    public function index()
    {
        $data = [
            'title'         => 'Hasil Survei',
            'content'       => 'survey_result',
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
            'note',
        ];

        $start  = $request->start;
        $length = $request->length;
        $order  = $column[$request->input('order.0.column')];
        $dir    = $request->input('order.0.dir');
        $search = $request->input('search.value');

        $total_data = SurveyResult::count();
        
        $query_data = SurveyResult::where(function($query) use ($search, $request) {
                if($search) {
                    $query->where(function($query) use ($search, $request) {
                        $query->where('code', 'like', "%$search%")
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

        $total_filtered = SurveyResult::where(function($query) use ($search, $request) {
                if($search) {
                    $query->where(function($query) use ($search, $request) {
                        $query->where('code', 'like', "%$search%")
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
                    $val->note,
                    $val->surveyResultDetail()->count(),
                    $val->statusBadge(),
                    '
                        <a href="javascript:void(0);" class="btn btn-info btn-sm content-icon" data-toggle="tooltip" data-placement="top" title="Upload Bukti" onclick="showUpload(`'.CustomHelper::encrypt($val->code).'`)"><i class="fa fa-upload"></i></a>
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
                'project_id'            => 'required',
                'post_date'             => 'required',
            ], [
                'project_id.required'           => 'Project tidak boleh kosong.',
                'post_date.required'            => 'Tgl. post tidak boleh kosong.',
            ]);

            if($validation->fails()) {
                $response = [
                    'status' => 422,
                    'error'  => $validation->errors()
                ];
            } else {
                if($request->temp){
                    $query = SurveyResult::where('code',CustomHelper::decrypt($request->temp))->first();

                    if($query->status == '3'){
                        return response()->json([
                            'status'    => 500,
                            'message'   => 'Ups. Surat SPK telah SELESAI, anda tidak bisa melakukan perubahan.'
                        ]);
                    }

                    $query->user_id             = session('bo_id');
                    $query->project_id          = $request->project_id;
                    $query->post_date           = $request->post_date;
                    $query->note                = $request->note;
                    $query->status              = '3';
                    $query->save();
                    CustomHelper::saveLog($query->getTable(),$query->id,'Update data hasil survei '.$query->code,'Pengguna '.session('bo_nama').' telah mengubah data hasil survei no '.$query->code);
                }else{
                    $query = SurveyResult::create([
                        'user_id'                   => session('bo_id'),
                        'code'                      => strtoupper(Str::random(15)),
                        'project_id'                => $request->project_id,
                        'post_date'                 => $request->post_date,
                        'note'                      => $request->note,
                        'status'                    => '3',
                    ]);
                    CustomHelper::saveLog($query->getTable(),$query->id,'Tambah baru data hasil survei '.$query->code,'Pengguna '.session('bo_nama').' telah manambahkan baru data hasil survei no '.$query->code);
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
        $data = SurveyResult::where('code',CustomHelper::decrypt($request->code))->first();
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

    public function showUpload(Request $request){
        $data = SurveyResult::where('code',CustomHelper::decrypt($request->code))->first();
        if($data){
            $images = [];

            foreach($data->surveyResultDetail as $rowfile){
                $images[] = [
                    'file'      => $rowfile->getFile(),
                    'code'      => CustomHelper::encrypt($rowfile->code),
                    'name'      => $rowfile->name,
                ];
            }

            $response = [
                'status'    => 200,
                'code'      => $data->code,
                'data'      => $images,
            ];
        }else{
            $response = [
                'status'  => 500,
                'message' => 'Data tidak ditemukan.'
            ];
        }

        return response()->json($response);
    }

    public function check(Request $request){		
        $query = SurveyResultDetail::where('name',$request->name)->first();
        
        if($query){
            return response()->json([
                'status'		=> 0,
            ]);
        }else{
            return response()->json([
                'status'		=> 1,
            ]);
        }
	}

    public function upload(Request $request){
        $validation = Validator::make($request->all(), [
			'file' 				=> 'required',
			'tempUpload'	    => 'required',
		]);

        if($validation->fails()) {
            $response = [
                'status' => 422,
                'error'  => $validation->errors()
            ];
        } else {

            /* $check = SurveyResult::where('name',$request->file('file')->getClientOriginalName())->first();

            if($check){
                $check->deleteFile();
                $query = $check->update([
                    'user_id'       => session('bo_id'),
                    'alias'         => $request->alias,
                    'category_id'   => $request->category,
                    'name'          => $request->file('file')->getClientOriginalName(),
                    'file_location'	=> $request->file('file') ? $request->file('file')->store('public/survey_result') : NULL
                ]);
                
            }else{ */
                $query = SurveyResult::where('code',CustomHelper::decrypt($request->code))->first();

                if($query){
                    $querydetail = SurveyResultDetail::create([
                        'survey_result_id'  => $query->id,
                        'code'	            => strtoupper(Str::random(15)),
                        'name'              => $request->file('file')->getClientOriginalName(),
                        'file_location'	    => $request->file('file') ? $request->file('file')->store('public/survey_result') : NULL
                    ]);
                }else{
                    $response = [
                        'status'		=> 200,
                        'message'		=> 'You have successfully upload the file.'
                    ];
                }
            /* } */
            
            
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
                CustomHelper::saveLog($query->getTable(),$query->id,'Surat SPK nomor '.$query->code.' telah dihapus.','Pengguna '.session('bo_nama').' telah menghapus data Surat SPK no '.$query->code);

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
    
            $pdf = Pdf::loadView('pdf.letter_agreement', $result)->setPaper('a4', 'portrait');
            $pdf->getDomPDF()->set_option("enable_php", true);
            /* $font = $pdf->getFontMetrics()->get_font("helvetica", "italic");
            $pdf->getCanvas()->page_text(260, 775, "{PAGE_NUM} of {PAGE_COUNT}", $font, 10, array(0,0,0)); */
            return $pdf->stream('letter_agreement_'.$data->code.'.pdf');
            /* return $pdf->download('invoice.pdf'); */
        }else{
            abort(404);
        }
    }
}
