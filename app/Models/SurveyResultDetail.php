<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class SurveyResultDetail extends Model
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $table = 'survey_result_details';
    protected $primaryKey = 'id';
    protected $dates = ['deleted_at'];
    protected $fillable = [
        'survey_result_id',
        'code',
        'alias',
        'name',
        'file_location',
    ];

    public function surveyResult(){
        return $this->belongsTo('App\Models\SurveyResult','survey_result_id','id')->withTrashed();
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
}