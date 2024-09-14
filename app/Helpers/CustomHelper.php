<?php

namespace App\Helpers;

use App\Models\Activity;
use App\Models\Approval;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
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

    public static function sendWhatsapp($phone,$message){
        $authkey = env('KEYWA');
        $appkey = env('APPKEY');
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://app.saungwa.com/api/create-message',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => array(
                'appkey'    => $appkey,
                'authkey'   => $authkey,
                'to'        => $phone,
                'message'   => $message,
                'sandbox'   => 'false'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        return $response;
    }

    public static function compress($source, $destination, $quality) {

		$info = getimagesize($source);
	
		if ($info['mime'] == 'image/jpeg') 
			$image = imagecreatefromjpeg($source);
	
		elseif ($info['mime'] == 'image/gif') 
			$image = imagecreatefromgif($source);
	
		elseif ($info['mime'] == 'image/png') 
			$image = imagecreatefrompng($source);
	
		imagejpeg($image, $destination, $quality);
	
		return $destination;
	}

    public static function sendApproval($table_name = null,$table_id = null,$url = null){
        Approval::where('lookable_type',$table_name)->where('lookable_id',$table_id)->delete();
        $userlevel1 = User::where('status','1')->where('type','7')->get();
        $userlevel2 = User::where('status','1')->where('type','8')->get();
        $message = '';
        
        $data = DB::table($table_name)->where('id',$table_id)->first();

        if($url == 'invoice'){
            $message = 'Dear Bapak/Ibu Pimpinan. Ijin menginformasikan bahwa dokumen Invoice No. '.$data->code.' telah dibayarkan dengan nomor kwitansi : '.$data->receipt_code.', mohon persetujuannya dengan menekan link terlampir : ';
        }

        foreach($userlevel1 as $row1){
            $dataaprove = Approval::create([
                'code'              => strtoupper(Str::random(15)),
                'from_user_id'      => session('bo_id'),
                'to_user_id'        => $row1->id,
                'lookable_type'     => $table_name,
                'lookable_id'       => $table_id,
                'url'               => $url,
                'approve_status'    => '1',
                'approve_level'     => 1,
            ]);
            if($row1->phone && $message){
                self::sendWhatsapp($row1->phone,$message.' '.env('APP_URL').'/persetujuan/detail/'.$dataaprove->code);
            }
        }

        foreach($userlevel2 as $row2){
            $dataaprove = Approval::create([
                'code'              => strtoupper(Str::random(15)),
                'from_user_id'      => session('bo_id'),
                'to_user_id'        => $row2->id,
                'lookable_type'     => $table_name,
                'lookable_id'       => $table_id,
                'url'               => $url,
                'approve_status'    => NULL,
                'approve_level'     => 2,
            ]);
        }
    }
}