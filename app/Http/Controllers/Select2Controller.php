<?php

namespace App\Http\Controllers;

use App\Helpers\CustomHelper;
use App\Models\Project;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Bank;
use App\Models\Customer;
use App\Models\ProjectType;
use App\Models\Purpose;
use App\Models\Region;

class Select2Controller extends Controller {
    
    public function customer(Request $request)
    {
        $response = [];
        $search   = $request->search;
        $data = Customer::where(function($query)use($search){
                $query->where('name', 'like', "%$search%")
                    ->orWhere('code','like',"%$search%");
            })
            ->where('status','1')
            ->get();

        foreach($data as $d) {
            $response[] = [
                'id'   			=> $d->id,
                'text' 			=> $d->code.' - '.$d->name,
            ];
        }

        return response()->json(['items' => $response]);
    }

    public function region(Request $request)
    {
        $response = [];
        $search   = $request->search;
        $data = Region::where(function($query)use($search){
                $query->where('name', 'like', "%$search%")
                    ->orWhere('code','like',"%$search%");
            })
            ->get();

        foreach($data as $d) {
            $response[] = [
                'id'   			=> $d->id,
                'text' 			=> $d->name,
            ];
        }

        return response()->json(['items' => $response]);
    }

    public function projectType(Request $request)
    {
        $response = [];
        $search   = $request->search;
        $data = ProjectType::where(function($query)use($search){
                $query->where('name', 'like', "%$search%")
                    ->orWhere('code','like',"%$search%");
            })
            ->where('status','1')
            ->get();

        foreach($data as $d) {
            $response[] = [
                'id'   			=> $d->id,
                'text' 			=> $d->name,
            ];
        }

        return response()->json(['items' => $response]);
    }

    public function purpose(Request $request)
    {
        $response = [];
        $search   = $request->search;
        $data = Purpose::where(function($query)use($search){
                $query->where('name', 'like', "%$search%")
                    ->orWhere('code','like',"%$search%");
            })
            ->where('status','1')
            ->get();

        foreach($data as $d) {
            $response[] = [
                'id'   			=> $d->id,
                'text' 			=> $d->name,
            ];
        }

        return response()->json(['items' => $response]);
    }

    public function project(Request $request)
    {
        $response = [];
        $search   = $request->search;
        $data = Project::where(function($query)use($search){
                    $query->where('code', 'like', "%$search%")
                        ->orWhere('name', 'like', "%$search%")
                        ->orWhere('project_no', 'like', "%$search%")
                        ->orWhere('location', 'like', "%$search%")
                        ->orWhereHas('customer', function($query) use ($search){
                            $query->where('code','like',"%$search%")
                                ->orWhere('name','like',"%$search%");
                        });
                })
                ->where('status','2')
                ->get();

        foreach($data as $d) {
            $response[] = [
                'id'   			=> $d->id,
                'text' 			=> $d->code.' - '.$d->name.' - '.$d->customer->name,
                'total_project' => number_format($d->cost,2,',','.'),
                'customer'      => $d->customer->name,
                'address'       => $d->customer->address,
                'phone'         => $d->customer->phone,
            ];
        }

        return response()->json(['items' => $response]);
    }

    public function bank(Request $request)
    {
        $response = [];
        $search   = $request->search;
        $data = Bank::where(function($query)use($search){
                $query->where('code', 'like', "%$search%")
                    ->orWhere('name','like',"%$search%")
                    ->orWhere('no','like',"%$search%")
                    ->orWhere('bank','like',"%$search%");
            })
            ->where('status','1')
            ->get();

        foreach($data as $d) {
            $response[] = [
                'id'   			=> $d->id,
                'text' 			=> $d->name.' - '.$d->no.' - '.$d->bank,
            ];
        }

        return response()->json(['items' => $response]);
    }
}
