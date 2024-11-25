<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Approval extends Model
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $table = 'approvals';
    protected $primaryKey = 'id';
    protected $dates = ['deleted_at'];
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
        'document',
    ];

    public function getFile(){
        if(Storage::exists($this->document)) {
            $document = '<a href="'.asset(Storage::url($this->document)).'"><i class="fa fa-search" aria-hidden="true"></i></a>';
        } else {
            $document = '-';
        }

        return $document;
    }

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

    public function approvalExceptMe($id){
        $data = Approval::where('lookable_type',$this->lookable_type)->where('lookable_id',$this->lookable_id)->whereIn('approve_status',['2','3'])->where('to_user_id','!=',$id)->get();
        return $data;
    }
}