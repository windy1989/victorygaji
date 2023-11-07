<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Models\Payroll;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $data = [
            'title'         => 'Dashboard',
            'content'       => 'main',
        ];

        return view('layouts.index', ['data' => $data]);
    }

    public function datatable(Request $request){
        $column = [
            'id',
            'rekening_bca',
            'bulan',
            'jabatan',
            'jumlah_transfer',
            'updated_at',
        ];

        $start  = $request->start;
        $length = $request->length;
        $order  = $column[$request->input('order.0.column')];
        $dir    = $request->input('order.0.dir');
        $search = $request->input('search.value');

        $total_data = Payroll::where('nik',session('bo_nik'))->count();
        
        $query_data = Payroll::where(function($query) use ($search, $request) {
                if($search) {
                    $query->where(function($query) use ($search, $request) {
                        $query->where('gaji_pokok', 'like', "%$search%")
                            ->orWhere('bulan','like',"%$search%")
                            ->orWhere('rekening_bca','like',"%$search%")
                            ->orWhere('jumlah_transfer','like',"%$search%");
                    });
                }
            })
            ->where('nik',session('bo_nik'))
            ->offset($start)
            ->limit($length)
            ->orderBy($order, $dir)
            ->get();

        $total_filtered = Payroll::where(function($query) use ($search, $request) {
                if($search) {
                    $query->where(function($query) use ($search, $request) {
                        $query->where('gaji_pokok', 'like', "%$search%")
                            ->orWhere('bulan','like',"%$search%")
                            ->orWhere('rekening_bca','like',"%$search%")
                            ->orWhere('jumlah_transfer','like',"%$search%");
                    });
                }
            })
            ->where('nik',session('bo_nik'))
            ->count();

        $response['data'] = [];
        if($query_data <> FALSE) {
            $nomor = $start + 1;
            foreach($query_data as $val) {
				
                $response['data'][] = [
                    $nomor,
                    $val->rekening_bca,
                    $val->bulan,
                    $val->jabatan,
                    number_format($val->jumlah_transfer,2,',','.'),
                    date('d/m/y H:i:s',strtotime($val->updated_at)),
                    '
                        <span style="font-size:25px;margin-left:10px;">
							<a href="javascript:void(0);" class="download-pdf" data-download="'.base64_encode($val->id).'"><i class="fas fa-download text-warning"></i></a>
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

    public function download(Request $request,$code){
        $data = Payroll::where('id',intval(base64_decode($code)))->where('nik',session('bo_nik'))->first();

        if($data){
            $img_path = 'assets/images/logovictory_2.png';
            $extencion = pathinfo($img_path, PATHINFO_EXTENSION);
            $image_temp = file_get_contents($img_path);
            $img_base_64 = base64_encode($image_temp);
            $path_img = 'data:image/' . $extencion . ';base64,' . $img_base_64;

            $pdf = Pdf::loadView('pdf.slip', [
                'data'  => $data->toArray(),
                'logo'  => $path_img
            ]);
            return $pdf->download('slip_gaji_bulan_'.$data->bulan.'.pdf');
        }else{
            abort(404);
        }
    }
}
