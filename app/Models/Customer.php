<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

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
        'logo',
        'status',
    ];

    public function attachment() 
    {
        if($this->logo !== NULL && Storage::exists($this->logo)) {
            $document = asset(Storage::url($this->logo));
        } else {
            $document = asset('website/empty.png');
        }

        return $document;
    }

    public function deleteFile(){
		if(Storage::exists($this->logo)) {
            Storage::delete($this->logo);
        }
	}

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

    public static function generateCode()
    {
        $prefix = 'P';

        $query = Customer::withTrashed()->selectRaw('RIGHT(code, 6) as code')
            ->orderByDesc('code')
            ->limit(1)
            ->get();

        if($query->count() > 0) {
            $code = intval($query[0]->code) + 1;
        } else {
            $code = '000001';
        }

        $no = str_pad($code, 6, 0, STR_PAD_LEFT);

        return $prefix.$no;
    }
}