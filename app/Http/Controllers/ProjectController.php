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
        
    }
}
