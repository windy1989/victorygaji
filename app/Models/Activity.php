<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Activity extends Model
{
    use HasFactory, Notifiable;

    protected $table = 'activities';
    protected $primaryKey = 'id';
    protected $fillable = [
        'user_id',
        'lookable_type',
        'lookable_id',
        'title',
        'note',
    ];

    public function lookable(){
        return $this->morphTo();
    }
}