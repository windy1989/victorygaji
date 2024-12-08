<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class LeaveDetail extends Model
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $table = 'leave_details';
    protected $primaryKey = 'id';
    protected $dates = ['deleted_at'];
    protected $fillable = [
        'leave_id',
        'date',
    ];

    public function leave(){
        return $this->belongsTo('App\Models\Leave','leave_id','id')->withTrashed();
    }
}