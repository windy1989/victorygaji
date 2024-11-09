<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Invoice extends Model
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $table = 'invoices';
    protected $primaryKey = 'id';
    protected $dates = ['deleted_at'];
    protected $fillable = [
        'code',
        'receipt_code',
        'user_id',
        'receive_from',
        'project_id',
        'bank_id',
        'post_date',
        'pay_date',
        'document',
        'percent_tax',
        'include_tax',
        'percent_wtax',
        'subtotal',
        'total',
        'tax',
        'total_after_tax',
        'wtax',
        'nominal',
        'termin_no',
        'note',
        'status',
        'void_id',
        'void_note',
        'void_date',
    ];

    public function includeTax(){
        $include_tax = match ($this->include_tax) {
            '1' => 'Include',
            '0' => 'Exclude',
            default => 'Invalid',
        };

        return $include_tax;
    }

    public function paymentNoText(){
        $text = '';
        if($this->termin_no == 1){
            $text = 'Pertama (DP)';
        }elseif($this->termin_no == 2){
            $text = 'Kedua';
        }elseif($this->termin_no == 3){
            $text = 'Ketiga';
        }elseif($this->termin_no == 3){
            $text = 'Keempat';
        }
        return $text;
    }

    public function user(){
        return $this->belongsTo('App\Models\User','user_id','id')->withTrashed();
    }

    public function attachment() 
    {
        if($this->document !== NULL && Storage::exists($this->document)) {
            $document = asset(Storage::url($this->document));
        } else {
            $document = asset('website/empty.png');
        }

        return $document;
    }

    public function deleteFile(){
		if(Storage::exists($this->document)) {
            Storage::delete($this->document);
        }
	}

    public function project(){
        return $this->belongsTo('App\Models\Project','project_id','id')->withTrashed();
    }

    public function bank(){
        return $this->belongsTo('App\Models\Bank','bank_id','id')->withTrashed();
    }

    public function voidUser(){
        return $this->belongsTo('App\Models\User','void_id','id')->withTrashed();
    }

    public function status(){
        $status = match ($this->status) {
            '1' => 'Menunggu',
            '2' => 'Proses',
            '3' => 'Selesai',
            '4' => 'Dibatalkan',
            '5' => 'Revisi/Ditolak',
            default => 'Invalid',
        };

        return $status;
    }

    public function statusBadge(){
        $status = match ($this->status) {
            '1' => '<span class="badge badge-warning">Menunggu</span>',
            '2' => '<span class="badge badge-secondary">Proses</span>',
            '3' => '<span class="badge badge-success">Selesai</span>',
            '4' => '<span class="badge badge-danger">Dibatalkan</span>',
            '5' => '<span class="badge badge-warning">Revisi/Ditolak</span>',
            default => '<span class="badge badge-danger">Invalid</span>',
        };

        return $status;
    }

    public function approval(){
        return $this->hasMany('App\Models\Approval', 'lookable_id', 'id')->where('lookable_type',$this->table);
    }
}