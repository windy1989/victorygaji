<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Purpose extends Model
{
    protected $table = 'purposes';
    protected $primaryKey = 'id';
    protected $dates = ['deleted_at'];
    protected $fillable = [
        'code',
        'name',
        'status',
    ];

    public function status(){
        $status = match ($this->status) {
            '1' => 'Aktif',
            default => 'Non-Aktif',
        };

        return $status;
    }

    public function statusBadge(){
        $status = match ($this->status) {
            '1' => '<span class="badge badge-success">Aktif</span>',
            default => '<span class="badge badge-danger">Non-Aktif</span>',
        };

        return $status;
    }
}
