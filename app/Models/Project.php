<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $table = 'projects';
    protected $primaryKey = 'id';
    protected $dates = ['deleted_at'];
    protected $fillable = [
        'code',
        'user_id',
        'name',
        'project_no',
        'post_date',
        'location',
        'region_id',
        'customer_id',
        'project_type_id',
        'purpose_id',
        'purpose_note',
        'working_days',
        'start_date',
        'end_date',
        'andalalin_document_no',
        'power_letter_no',
        'cost',
        'termin',
        'note',
        'status',
        'void_id',
        'void_note',
        'void_date',
    ];

    public function user(){
        return $this->belongsTo('App\Models\User','user_id','id')->withTrashed();
    }

    public function city(){
        return $this->belongsTo('App\Models\Region','region_id','id')->withTrashed();
    }

    public function customer(){
        return $this->belongsTo('App\Models\Customer','customer_id','id')->withTrashed();
    }
    
    public function projectType(){
        return $this->belongsTo('App\Models\ProjectType','project_type_id','id')->withTrashed();
    }

    public function purpose(){
        return $this->belongsTo('App\Models\Purpose','purpose_id','id')->withTrashed();
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
            default => '<span class="badge badge-danger">Invalid</span>',
        };

        return $status;
    }

    public static function generateCode()
    {
        $prefix = 'PRJ';

        $query = Project::withTrashed()->selectRaw('RIGHT(code, 6) as code')
            ->orderByDesc('code')
            ->limit(1)
            ->get();

        if($query->count() > 0) {
            $code = intval($query[0]->code) + 1;
        } else {
            $code = '000001';
        }

        $no = str_pad($code, 6, 0, STR_PAD_LEFT);

        return $prefix.$no;
    }
}