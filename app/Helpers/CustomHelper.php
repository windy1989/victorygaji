<?php

namespace App\Helpers;

use App\Models\Activity;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
class CustomHelper {
    public static function saveLog($table_name,$table_id,$title,$note){
        Activity::create([
            'user_id'       => session('bo_id'),
            'lookable_type' => $table_name,
            'lookable_id'   => $table_id,
            'title'         => $title,
            'note'          => $note,
        ]);
    }

    public static function encrypt($string){
        if($string == ''){
			$val = "";
		}else{
			$val = strrev(implode('-',str_split(str_replace('=','',base64_encode($string)),5)));
		}
		
		return $val;
    }

    public static function decrypt($string){
		$val = base64_decode(str_replace('-','',strrev($string)));
		return $val;
	}
}