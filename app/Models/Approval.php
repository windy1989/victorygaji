<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Approval extends Model
{
    use HasFactory, Notifiable;

    protected $table = 'approvals';
    protected $primaryKey = 'id';
    protected $fillable = [
        'code',
        'from_user_id',
        'to_user_id',
        'approve_note',
        'approve_status',
        'approve_level',
        'approve_date',
        'lookable_type',
        'lookable_id',
        'url',
    ];

    public function approveStatus(){
        $approve_status = match ($this->approve_status) {
            '1' => 'Proses',
            '2' => 'Disetujui',
            '3' => 'Ditolak',
            default => 'Menunggu',
        };

        return $approve_status;
    }

    public function fromUser(){
        return $this->belongsTo('App\Models\User','from_user_id','id')->withTrashed();
    }

    public function toUser(){
        return $this->belongsTo('App\Models\User','to_user_id','id')->withTrashed();
    }

    public function lookable(){
        return $this->morphTo();
    }
}