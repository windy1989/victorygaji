<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class OfferingLetter extends Model
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $table = 'offering_letters';
    protected $primaryKey = 'id';
    protected $dates = ['deleted_at'];
    protected $fillable = [
        'code',
        'user_id',
        'project_id',
        'post_date',
        'to_name',
        'type_building',
        'location_building',
        'type_road',
        'is_pnbp',
        'is_include_tax',
        'note',
        'status',
    ];

    public function user(){
        return $this->belongsTo('App\Models\User','user_id','id')->withTrashed();
    }

    public function project(){
        return $this->belongsTo('App\Models\Project','project_id','id')->withTrashed();
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

    public function isPnbp(){
        $status = match ($this->is_pnbp) {
            '1' => 'include PNBP',
            '2' => 'exclude PNBP',
            default => 'Invalid',
        };

        return $status;
    }

    public function isIncludeTax(){
        $status = match ($this->is_include_tax) {
            '1' => 'include Pajak',
            '2' => 'exclude Pajak',
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