<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Payroll extends Model
{
    use HasFactory, Notifiable;

    protected $table = 'payrolls';
    protected $primaryKey = 'id';
    protected $fillable = [
        'nik',
        'rekening_bca',
        'bulan',
        'jabatan',
        'telepon',
        'status',
        'gaji_pokok',
        'jumlah_lembur',
        'total_lembur',
        'jumlah_potongan_terlambat',
        'total_potongan_terlambat',
        'total_potongan_kehadiran',
        'total_potongan_pinjaman',
        'total_potongan_denda',
        'total_potongan_bpjs',
        'tunjangan_pengganti',
        'jumlah_transfer',
    ];

    public function user(){
        return $this->belongsTo('App\Models\User','nik','nik')->withTrashed();
    }

    public function historyEmail()
    {
        return $this->hasMany('App\Models\HistoryEmail','payroll_id','id');
    }

    public static function createUser($data){
        $user = User::where('nik',$data['nik'])->where('status','1')->first();
        if(!$user){
            $pass = Str::random(6);
            User::create([
                'nama'      => $data['nama'],
                'nik'       => $data['nik'],
                'email'     => $data['email'],
                'password'  => bcrypt($pass),
                'type'      => '2',
                'status'    => '1',
                'phone'     => $data['telepon'],
                'code'      => base64_encode($pass),
            ]);
        }
    }
}