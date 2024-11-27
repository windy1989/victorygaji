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
		$userlevel1 = '';
		$userlevel2 = '';
		$userlevel3 = '';
		$userlevel4 = '';
		if($url == 'proyek' || $url == 'surat_penawaran'){
			$userlevel1 = User::where('status','1')->where('type','06')->get();
        	$userlevel2 = User::where('status','1')->where('type','07')->get();
		}
		if($url == 'spk' || $url == 'kelengkapan_dokumen'){
			$userlevel1 = User::where('status','1')->where('type','06')->get();
        	$userlevel2 = User::where('status','1')->where('type','07')->get();
			$userlevel3 = User::where('status','1')->where('type','08')->get();
		}
		if($url == 'dokumen_andalalin' || $url == 'revisi'){
			$userlevel1 = User::where('status','1')->where('type','12')->get();
        	$userlevel2 = User::where('status','1')->where('type','06')->get();
			$userlevel3 = User::where('status','1')->where('type','07')->get();
			$userlevel4 = User::where('status','1')->where('type','08')->get();
		}
		if($url == 'disposisi_legalitas'){
			$userlevel1 = User::where('status','1')->where('type','12')->get();
        	$userlevel2 = User::where('status','1')->where('type','06')->get();
			$userlevel3 = User::where('status','1')->where('type','07')->get();
		}
		if($url == 'invoice'){
			$userlevel1 = User::where('status','1')->where('type','06')->get();
			$userlevel2 = User::where('status','1')->where('type','07')->get();
			$userlevel3 = User::where('status','1')->where('type','08')->get();
		}
		if($url == 'drafter' || $url == 'revisi_drafter'){
			$userlevel1 = User::where('status','1')->where('type','11')->get();
        	$userlevel2 = User::where('status','1')->where('type','06')->get();
			$userlevel3 = User::where('status','1')->where('type','07')->get();
			$userlevel4 = User::where('status','1')->where('type','08')->get();
		}

        $message = '';
        
        $data = DB::table($table_name)->where('id',$table_id)->first();

        if($url == 'invoice'){
            $message = 'Dear Bapak/Ibu Pimpinan. Ijin menginformasikan bahwa dokumen Invoice No. '.$data->code.' telah dibayarkan dengan nomor kwitansi : '.$data->receipt_code.', mohon persetujuannya dengan menekan link terlampir : ';
        }

		if($url == 'surat_penawaran'){
            $message = 'Dear Bapak/Ibu Pimpinan. Ijin menginformasikan bahwa dokumen Surat Penawaran No. '.$data->code.' telah dibuat, mohon persetujuannya dengan menekan link terlampir : ';
        }

		if($url == 'spk'){
            $message = 'Dear Bapak/Ibu Pimpinan. Ijin menginformasikan bahwa dokumen SPK No. '.$data->code.' telah dibuat, mohon persetujuannya dengan menekan link terlampir : ';
        }

		if($url == 'proyek'){
            $message = 'Dear Bapak/Ibu Pimpinan. Ijin menginformasikan bahwa dokumen Proyek No. '.$data->code.' telah dibuat, mohon persetujuannya dengan menekan link terlampir : ';
        }

		if($url == 'kelengkapan_dokumen'){
            $message = 'Dear Bapak/Ibu Pimpinan. Ijin menginformasikan bahwa dokumen Kelengkapan Dokumen No. '.$data->code.' telah dibuat, mohon persetujuannya dengan menekan link terlampir : ';
        }

		if($url == 'dokumen_andalalin'){
            $message = 'Dear Bapak/Ibu Pimpinan. Ijin menginformasikan bahwa dokumen Andalalin No. '.$data->code.' telah dibuat, mohon persetujuannya dengan menekan link terlampir : ';
        }

		if($url == 'revisi'){
            $message = 'Dear Bapak/Ibu Pimpinan. Ijin menginformasikan bahwa dokumen Revisi No. '.$data->code.' telah dibuat, mohon persetujuannya dengan menekan link terlampir : ';
        }

		if($url == 'drafter'){
            $message = 'Dear Bapak/Ibu Pimpinan. Ijin menginformasikan bahwa dokumen Drafter No. '.$data->code.' telah dibuat, mohon persetujuannya dengan menekan link terlampir : ';
        }

		if($url == 'revisi_drafter'){
            $message = 'Dear Bapak/Ibu Pimpinan. Ijin menginformasikan bahwa dokumen Revisi Drafter No. '.$data->code.' telah dibuat, mohon persetujuannya dengan menekan link terlampir : ';
        }

		if($url == 'disposisi_legalitas'){
            $message = 'Dear Bapak/Ibu Pimpinan. Ijin menginformasikan bahwa dokumen Disposisi Legalitas Syarat Andalalin No. '.$data->code.' telah dibuat, mohon persetujuannya dengan menekan link terlampir : ';
        }

		if($userlevel1){
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
		}

		if($userlevel2){
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

		if($userlevel3){
			foreach($userlevel3 as $row3){
				$dataaprove = Approval::create([
					'code'              => strtoupper(Str::random(15)),
					'from_user_id'      => session('bo_id'),
					'to_user_id'        => $row3->id,
					'lookable_type'     => $table_name,
					'lookable_id'       => $table_id,
					'url'               => $url,
					'approve_status'    => NULL,
					'approve_level'     => 3,
				]);
			}
		}

		if($userlevel4){
			foreach($userlevel4 as $row4){
				$dataaprove = Approval::create([
					'code'              => strtoupper(Str::random(15)),
					'from_user_id'      => session('bo_id'),
					'to_user_id'        => $row4->id,
					'lookable_type'     => $table_name,
					'lookable_id'       => $table_id,
					'url'               => $url,
					'approve_status'    => NULL,
					'approve_level'     => 4,
				]);
			}
		}
    }

    public static function terbilangWithKoma($angka){
		$arr = explode('.',strval(round($angka,2)));
		$angka=intval($arr[0]);
		$sen = '';
		if(count($arr) > 1){
			$sen = self::tkoma($arr[1]);
		}

		$terbilang = self::terbilang($angka).(count($arr) > 1 ? ' Koma '.$sen : '');

		return $terbilang;
	}

	public static function terbilang($angka) {
		$angka = strval($angka);
		
		$baca = array("nol", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas");
	  
		$terbilang="";
		 if ($angka < 12){
			 $terbilang= " " . $baca[$angka];
		 }
		 else if ($angka < 20){
			 $terbilang= self::terbilangSen($angka - 10) . " belas";
		 }
		 else if ($angka < 100){
			 $terbilang= self::terbilangSen($angka / 10) . " puluh" . self::terbilangSen($angka % 10);
		 }
		 else if ($angka < 200){
			 $terbilang= " seratus" . self::terbilangSen($angka - 100);
		 }
		 else if ($angka < 1000){
			 $terbilang= self::terbilangSen($angka / 100) . " ratus" . self::terbilangSen($angka % 100);
		 }
		 else if ($angka < 2000){
			 $terbilang= " seribu" . self::terbilangSen($angka - 1000);
		 }
		 else if ($angka < 1000000){
			 $terbilang= self::terbilangSen($angka / 1000) . " ribu" . self::terbilangSen($angka % 1000);
		 }
		 else if ($angka < 1000000000){
			$terbilang= self::terbilangSen($angka / 1000000) . " juta" . self::terbilangSen($angka % 1000000);
		 }
		 else if ($angka < 1000000000000){
			$terbilang= self::terbilangSen($angka / 1000000000) . " miliar" . self::terbilangSen($angka % 1000000000);
		 }
		 
		 return ucwords($terbilang);
	 }

	public static function tkoma($angka){
		$baca =array("nol", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan");

		$temp = "";
		$pjg = strlen($angka);
		$pos = 0;

		while($pos < $pjg){
			$char =	 substr($angka,$pos,1);
			$pos++;
			$temp	.= " ".$baca[$char];
		}

		return ucwords($temp);
	}	

	 public static function terbilangSen($angka) {
		$angka=abs($angka);
		
		$baca =array("", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas");
	  
		$terbilang="";
		 if ($angka < 12){
			 $terbilang= " " . $baca[$angka];
		 }
		 else if ($angka < 20){
			 $terbilang= self::terbilangSen($angka - 10) . " belas";
		 }
		 else if ($angka < 100){
			 $terbilang= self::terbilangSen($angka / 10) . " puluh" . self::terbilangSen($angka % 10);
		 }
		 else if ($angka < 200){
			 $terbilang= " seratus" . self::terbilangSen($angka - 100);
		 }
		 else if ($angka < 1000){
			 $terbilang= self::terbilangSen($angka / 100) . " ratus" . self::terbilangSen($angka % 100);
		 }
		 else if ($angka < 2000){
			 $terbilang= " seribu" . self::terbilangSen($angka - 1000);
		 }
		 else if ($angka < 1000000){
			 $terbilang= self::terbilangSen($angka / 1000) . " ribu" . self::terbilangSen($angka % 1000);
		 }
		 else if ($angka < 1000000000){
			$terbilang= self::terbilangSen($angka / 1000000) . " juta" . self::terbilangSen($angka % 1000000);
		 }
		 else if ($angka < 1000000000000){
			$terbilang= self::terbilangSen($angka / 1000000000) . " miliar" . self::terbilangSen($angka % 1000000000);
		 }
		 
		 return ucwords($terbilang);
	 }

	 public static function tgl_indo($tanggal){
		$bulan = array (
			1 =>   'Januari',
			'Februari',
			'Maret',
			'April',
			'Mei',
			'Juni',
			'Juli',
			'Agustus',
			'September',
			'Oktober',
			'November',
			'Desember'
		);
		$pecahkan = explode('-', $tanggal);
	 
		return $pecahkan[2] . ' ' . $bulan[ (int)$pecahkan[1] ] . ' ' . $pecahkan[0];
	}

	public static function bulan($tanggal){
		$bulan = array (
			1 =>   'Januari',
			'Februari',
			'Maret',
			'April',
			'Mei',
			'Juni',
			'Juli',
			'Agustus',
			'September',
			'Oktober',
			'November',
			'Desember'
		);
		$pecahkan = explode('-', $tanggal);
	 
		return $bulan[ (int)$pecahkan[1] ];
	}
	
	public static function hari($tanggal){
		$hari = date ("D",strtotime($tanggal));

		$hari_ini = match ($hari) {
            'Sun' 	=> 'Minggu',
            'Mon' 	=> 'Senin',
            'Tue' 	=> 'Selasa',
            'Wed'	=> 'Rabu',
            'Thu' 	=> 'Kamis',
			'Fri'	=> 'Jumat',
			'Sat'	=> 'Sabtu',
            default => 'Invalid',
        };
	
		return $hari_ini;
	}

	public static function tahap($number){
		$tahap = match ($number) {
            1 	=> 'I',
            2 	=> 'II',
            3   => 'III',
            default => 'Invalid',
        };
	
		return $tahap;
	}

	public static function countDays($start_date, $end_date){
		$date1 = strtotime($start_date);
		$date2 = strtotime($end_date);
		$diff = $date2 - $date1;
		$days = floor($diff / (60 * 60 * 24));
		return $days;
	}
}