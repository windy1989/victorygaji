<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nama',
        'nik',
        'email',
        'email_verified_at',
        'password',
        'remember_token',
        'type',
        'status',
        'code',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
    ];

    public function status(){
        $status = match ($this->status) {
            '1' => 'Aktif',
            '2' => 'Non-Aktif',
            default => 'Invalid',
        };

        return $status;
    }

    public function type(){
        $type = match ($this->type) {
            '1' => 'Superadmin',
            '2' => 'Karyawan',
            default => 'Invalid',
        };

        return $type;
    }
}
