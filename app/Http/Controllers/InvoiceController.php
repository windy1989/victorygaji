<?php

namespace App\Http\Controllers;

use App\Helpers\CustomHelper;
use App\Http\Controllers\Controller;
use App\Models\Bank;
use App\Models\Customer;
use App\Models\Invoice;
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

class InvoiceController extends Controller
{
    public function index()
    {
        $data = [
            'title'         => 'Invoice',
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
            'percent_tax',
            'include_tax',
            'percent_wtax',
            'subtotal',
            'total',
            'tax',
            'total_after_tax',
            'wtax',
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
                            ->orWhere('receipt_code', 'like', "%$search%")
                            ->orWhere('receive_from', 'like', "%$search%")
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

        $total_filtered = Invoice::where(function($query) use ($search, $request) {
                if($search) {
                    $query->where(function($query) use ($search, $request) {
                        $query->where('code', 'like', "%$search%")
                            ->orWhere('receipt_code', 'like', "%$search%")
                            ->orWhere('receive_from', 'like', "%$search%")
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
                    $val->receipt_code ?? '-',
                    $val->user->nama,
                    $val->receive_from,
                    $val->project->project_no.' - '.$val->project->customer->name,
                    $val->bank->no.' - '.$val->bank->bank,
                    date('d/m/Y',strtotime($val->post_date)),
                    $val->pay_date ? date('d/m/Y',strtotime($val->pay_date)) : '-',
                    number_format($val->percent_tax,2,',','.'),
                    $val->includeTax(),
                    number_format($val->percent_wtax,2,',','.'),
                    number_format($val->subtotal,2,',','.'),
                    number_format($val->total,2,',','.'),
                    number_format($val->tax,2,',','.'),
                    number_format($val->total_after_tax,2,',','.'),
                    number_format($val->wtax,2,',','.'),
                    number_format($val->nominal,2,',','.'),
                    $val->termin_no,
                    $val->note,
                    $val->statusBadge(),
                    $val->document ? '<a href="'.$val->attachment().'" target="_blank"><i class="flaticon-381-link"></i></a>' : 'Belum diunggah',
                    '
                        <a href="javascript:void(0);" class="btn btn-secondary btn-sm content-icon" onclick="detail(`'.CustomHelper::encrypt($val->code).'`)"><i class="fa fa-info-circle"></i></a>
                        <a href="javascript:void(0);" class="btn btn-success btn-sm content-icon" onclick="pay(`'.CustomHelper::encrypt($val->code).'`,`'.$val->code.'`)" data-toggle="tooltip" data-placement="top" title="Bayar Invoice"><i class="fa fa-credit-card-alt"></i></a>
                        <a href="'.env('APP_URL').'/invoice/print/'.CustomHelper::encrypt($val->code).'" class="btn btn-info btn-sm content-icon" data-toggle="tooltip" data-placement="top" title="Cetak Invoice"><i class="fa fa-print"></i></a>
                        <a href="'.env('APP_URL').'/invoice/print_receipt/'.CustomHelper::encrypt($val->code).'" class="btn btn-light btn-sm content-icon" data-toggle="tooltip" data-placement="top" title="Cetak Kwitansi"><i class="fa fa-print"></i></a>
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
                'code'		            => $request->temp ? ['required', Rule::unique('invoices', 'code')->ignore(CustomHelper::decrypt($request->temp),'code')] : 'required|unique:invoices,code',
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

                    if($query->status == '3'){
                        return response()->json([
                            'status'    => 500,
                            'message'   => 'Ups. Invoice telah SELESAI, anda tidak bisa melakukan perubahan.'
                        ]);
                    }

                    $query->user_id         = session('bo_id');
                    $query->code            = $request->code;
                    $query->receive_from    = $request->receive_from;    
                    $query->project_id      = $request->project_id;
                    $query->bank_id         = $request->bank_id;
                    $query->post_date       = $request->post_date;
                    $query->percent_tax     = str_replace(',','.',str_replace('.','',$request->percent_tax));
                    $query->include_tax     = $request->include_tax;
                    $query->percent_wtax    = str_replace(',','.',str_replace('.','',$request->percent_wtax));
                    $query->subtotal        = str_replace(',','.',str_replace('.','',$request->subtotal));
                    $query->total           = str_replace(',','.',str_replace('.','',$request->total));
                    $query->tax             = str_replace(',','.',str_replace('.','',$request->tax)); 
                    $query->total_after_tax = str_replace(',','.',str_replace('.','',$request->total_after_tax));
                    $query->wtax            = str_replace(',','.',str_replace('.','',$request->wtax));
                    $query->nominal         = str_replace(',','.',str_replace('.','',$request->nominal));
                    $query->termin_no       = $request->termin_no;
                    $query->note            = $request->note;
                    $query->status          = '1';
                    $query->save();
                    CustomHelper::saveLog($query->getTable(),$query->id,'Update data invoice '.$query->code,'Pengguna '.session('bo_nama').' telah mengubah data invoice no '.$query->code);
                }else{
                    $query = Invoice::create([
                        'user_id'         => session('bo_id'),
                        'code'            => $request->code,
                        'receive_from'    => $request->receive_from,
                        'project_id'      => $request->project_id,
                        'bank_id'         => $request->bank_id,
                        'post_date'       => $request->post_date,
                        'percent_tax'     => str_replace(',','.',str_replace('.','',$request->percent_tax)),
                        'include_tax'     => $request->include_tax,
                        'percent_wtax'    => str_replace(',','.',str_replace('.','',$request->percent_wtax)),
                        'subtotal'        => str_replace(',','.',str_replace('.','',$request->subtotal)),
                        'total'           => str_replace(',','.',str_replace('.','',$request->total)),
                        'tax'             => str_replace(',','.',str_replace('.','',$request->tax)),
                        'total_after_tax' => str_replace(',','.',str_replace('.','',$request->total_after_tax)),
                        'wtax'            => str_replace(',','.',str_replace('.','',$request->wtax)),
                        'nominal'         => str_replace(',','.',str_replace('.','',$request->nominal)),
                        'termin_no'       => $request->termin_no,
                        'note'            => $request->note,
                        'status'          => '1',
                    ]);
                    CustomHelper::saveLog($query->getTable(),$query->id,'Tambah baru data invoice '.$query->code,'Pengguna '.session('bo_nama').' telah manambahkan baru data invoice no '.$query->code);
                }
                
                if($query) {
                    CustomHelper::sendApproval($query->getTable(),$query->id,'invoice');
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

                if($query->status == '3'){
                    return response()->json([
                        'status'    => 500,
                        'message'   => 'Ups. Invoice telah SELESAI, anda tidak bisa melakukan perubahan.'
                    ]);
                }

                if($query->document){
                    if(Storage::exists($query->document)){
                        Storage::delete($query->document);
                    }
                }

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
                
                if($query) {
                    CustomHelper::saveLog($query->getTable(),$query->id,'Update pembayaran data invoice '.$query->code,'Pengguna '.session('bo_nama').' telah mengubah data invoice no '.$query->code);
                    CustomHelper::sendApproval($query->getTable(),$query->id,'invoice');

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
            info($e->getMessage());
            DB::rollback();
        }
    }

    public function show(Request $request){
        $data = Invoice::where('code',CustomHelper::decrypt($request->code))->first();
        if($data){
            $data['project_code'] = $data->project->code.' - '.$data->project->name.' - '.$data->project->customer->name;
            $data['bank_code'] = $data->bank->name.' - '.$data->bank->no.' - '.$data->bank->bank;
            $data['nominal'] = number_format($data->nominal,2,',','.');
            $data['subtotal'] = number_format($data->subtotal,2,',','.');
            $data['total'] = number_format($data->total,2,',','.');
            $data['tax'] = number_format($data->tax,2,',','.');
            $data['wtax'] = number_format($data->wtax,2,',','.');
            $data['total_after_tax'] = number_format($data->total_after_tax,2,',','.');
            $data['percent_tax'] = number_format($data->percent_tax,2,',','.');
            $data['percent_wtax'] = number_format($data->percent_wtax,2,',','.');
            $data['nominal_project'] = number_format($data->project->cost,2,',','.');
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
        $data = Invoice::where('code',CustomHelper::decrypt($request->code))->first();
        if($data){

            $html = '';

            if($data->approval()->exists()){
                $html .= '<table class="table table-responsive-md">
                        <thead>
                            <tr>
                                <th><strong>#</strong></th>
                                <th><strong>APPROVER</strong></th>
                                <th><strong>LEVEL</strong></th>
                                <th><strong>TGL.APPROVE</strong></th>
                                <th><strong>STATUS</strong></th>
                                <th><strong>CATATAN</strong></th>
                                <th><strong>GAMBAR</strong></th>
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
                        <td>'.$row->getFile().'</td>
                    </tr>';
                }

                $html .= '</tbody></table>';
            }else{
                $html = 'Anda belum mengupload bukti bayar, silahkan upload dengan tombol HIJAU.';
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
        $query = Invoice::where('code',CustomHelper::decrypt($request->code))->first();
        if($query){
            if($query->status == '1'){
                CustomHelper::saveLog($query->getTable(),$query->id,'Invoice nomor '.$query->code.' telah dihapus.','Pengguna '.session('bo_nama').' telah menghapus data invoice no '.$query->code);

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
        $data = Invoice::where('code',CustomHelper::decrypt($id))->first();
        if($data){

            $result = [
                'title'         => 'Invoice '.$data->code,
                'data'          => $data,
                'banks'         => Bank::where('status','1')->get(),
            ];
    
            $pdf = Pdf::loadView('pdf.invoice', $result);
            return $pdf->stream('invoice_'.$data->code.'.pdf');
            /* return $pdf->download('invoice.pdf'); */
        }else{
            abort(404);
        }
    }

    public function printReceipt(Request $request,$id){
        $data = Invoice::where('code',CustomHelper::decrypt($id))->whereNotNull('receipt_code')->first();
        if($data){

            $result = [
                'title'         => 'Kwitansi '.$data->receipt_code,
                'data'          => $data,
            ];
    
            $pdf = Pdf::loadView('pdf.receipt', $result);
            return $pdf->stream('receipt_'.$data->receipt_code.'.pdf');
            /* return $pdf->download('invoice.pdf'); */
        }else{
            abort(404);
        }
    }
}
