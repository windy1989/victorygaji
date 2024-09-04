<?php

namespace App\Http\Controllers;

use App\Helpers\CustomHelper;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
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
}
