<?php

namespace App\Http\Controllers;

use App\Helpers\CustomHelper;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Customer;

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
}
