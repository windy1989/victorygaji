<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class MitigationDetail extends Model
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $table = 'mitigation_details';
    protected $primaryKey = 'id';
    protected $dates = ['deleted_at'];
    protected $fillable = [
        'mitigation_id',
        'code',
        'alias',
        'name',
        'file_location',
    ];

    public function mitigation(){
        return $this->belongsTo('App\Models\Mitigation','mitigation_id','id')->withTrashed();
    }

    public function attachment() 
    {
        if($this->file_location !== NULL && Storage::exists($this->file_location)) {
            $file_location = asset(Storage::url($this->file_location));
        } else {
            $file_location = asset('website/empty.png');
        }

        return $file_location;
    }

    public function deleteFile(){
		if(Storage::exists($this->file_location)) {
            Storage::delete($this->file_location);
        }
	}

    public function getFile(){
        if(Storage::exists($this->file_location)) {
            if(in_array(explode('.',$this->file_location)[1],['docx','doc'])){
                $document = '<a href="'.asset(Storage::url($this->file_location)).'" target="_blank"><img src="'.asset('assets/images/docx.png').'" style="max-height:100px;"></a>';
            }elseif(explode('.',$this->file_location)[1] == 'pdf'){
                $document = '<a href="'.asset(Storage::url($this->file_location)).'" target="_blank"><img src="'.asset('assets/images/pdf.png').'" style="max-height:100px;"></a>';
            }elseif(in_array(explode('.',$this->file_location)[1],['xls','xlsx'])){
                $document = '<a href="'.asset(Storage::url($this->file_location)).'" target="_blank"><img src="'.asset('assets/images/excel.png').'" style="max-height:100px;"></a>';
            }else{ 
                $document = '<img src="'.asset(Storage::url($this->file_location)).'" style="max-height:100px;">';
            }
        } else {
            $document = asset('website/empty_profile.png');
        }

        return $document;
    }
}