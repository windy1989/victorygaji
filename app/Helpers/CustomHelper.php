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
}