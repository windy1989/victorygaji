<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $dates = ['deleted_at'];

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
        'phone',
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

    public function statusBadge(){
        $status = match ($this->status) {
            '1' => '<span class="badge badge-success">Aktif</span>',
            default => '<span class="badge badge-danger">Non-Aktif</span>',
        };

        return $status;
    }

    public function type(){
        $type = match ($this->type) {
            '01' => 'Superadmin',
            '02' => 'Karyawan',
            '03' => 'Dokumen',
            '04' => 'Drafter',
            '05' => 'Surveyor',
            '06' => 'Manager',
            '07' => 'Director',
            '08' => 'Komisaris',
            '09' => 'Supervisor Admin',
            '10' => 'Supervisor Surveyor',
            '11' => 'Supervisor Drafter',
            '12' => 'Supervisor Dokumen',
            default => 'Invalid',
        };

        return $type;
    }
}
