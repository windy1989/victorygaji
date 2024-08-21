<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $table = 'customers';
    protected $primaryKey = 'id';
    protected $dates = ['deleted_at'];
    protected $fillable = [
        'code',
        'name',
        'email',
        'owner_name',
        'pic',
        'owner_id_card',
        'company_name',
        'document_no',
        'address',
        'city',
        'gender',
        'phone',
        'type_body',
        'note',
        'status',
    ];

    public function status(){
        $status = match ($this->status) {
            '1' => 'Aktif',
            default => 'Non-Aktif',
        };

        return $status;
    }
}