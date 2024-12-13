<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Leave extends Model
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $table = 'leaves';
    protected $primaryKey = 'id';
    protected $dates = ['deleted_at'];
    protected $fillable = [
        'code',
        'user_id',
        'employee_id',
        'post_date',
        'note',
        'status',
        'void_id',
        'void_note',
        'void_date',
    ];

    public function user(){
        return $this->belongsTo('App\Models\User','user_id','id')->withTrashed();
    }
    
    public function employee(){
        return $this->belongsTo('App\Models\User','employee_id','id')->withTrashed();
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

    public function leaveDetail(): mixed{
        return $this->hasMany('App\Models\LeaveDetail');
    }

    public function getDateDetail(){
        $arr = [];
        foreach($this->leaveDetail as $row){
            $arr[] = date('d/m/Y',strtotime($row->date));
        }
        return implode(', ',$arr);
    }
}