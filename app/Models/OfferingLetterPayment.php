<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\SoftDeletes;

class OfferingLetterPayment extends Model
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $table = 'offering_letter_payments';
    protected $primaryKey = 'id';
    protected $dates = ['deleted_at'];
    protected $fillable = [
        'offering_letter_id',
        'termin',
        'percentage',
        'note',
    ];

    public function offeringLetter(){
        return $this->belongsTo('App\Models\OfferingLetter','offering_letter_id','id')->withTrashed();
    }
}