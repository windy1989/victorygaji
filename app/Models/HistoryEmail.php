<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class HistoryEmail extends Model
{
    use HasFactory, Notifiable;

    protected $table = 'history_emails';
    protected $primaryKey = 'id';
    protected $fillable = [
        'payroll_id',
        'date_process',
        'status',
    ];

    public function payroll(){
        return $this->belongsTo('App\Models\Payroll','payroll_id','id');
    }

    public function status(){
        $status = match ($this->status) {
            '1' => 'Gagal',
            '2' => 'Sukses',
            default => 'Invalid',
        };

        return $status;
    }
}