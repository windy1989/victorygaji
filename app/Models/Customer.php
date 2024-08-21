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

    public function gender(){
        $gender = match ($this->gender) {
            '1' => 'Laki-laki',
            '2' => 'Perempuan',
            '3' => 'Lain-lain',
            default => 'Invalid',
        };

        return $gender;
    }

    public function typeBody(){
        $type_body = match ($this->type_body) {
            '1' => 'PT',
            '2' => 'CV',
            '3' => 'Perorangan',
            default => 'Invalid',
        };

        return $type_body;
    }
}