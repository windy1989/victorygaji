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

    public function getTimeAgo()
    {
        $time_difference = time() - strtotime($this->created_at);

        if( $time_difference < 1 ) { return 'less than 1 second ago'; }
        $condition = array( 12 * 30 * 24 * 60 * 60 =>  'year',
                    30 * 24 * 60 * 60       =>  'month',
                    24 * 60 * 60            =>  'day',
                    60 * 60                 =>  'hour',
                    60                      =>  'minute',
                    1                       =>  'second'
        );

        foreach( $condition as $secs => $str )
        {
            $d = $time_difference / $secs;

            if( $d >= 1 )
            {
                $t = round( $d );
                return 'sekitar ' . $t . ' ' . $str . ( $t > 1 ? 's' : '' ) . ' yang lalu';
            }
        }
    }
}