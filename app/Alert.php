<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Alert extends Model
{
    use SoftDeletes;
    
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'alerts_table';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'alert_id';

     /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'occured_at', 'resolved_at', 'action'
    ];

    /**
     * Get the reader where the alert occurs
     */
    public function reader()
    {
        return $this->belongsTo(Reader::class, 'reader_id', 'gateway_id');//->withTrashed();
    }

    /**
     * Get the policy that triggers the alert
     */
    public function policy()
    {
        return $this->belongsTo(Policy::class, 'rules_id', 'rules_id')->withTrashed();
    }

    /**
     * Get the tag that triggers the alert
     */
    public function tag()
    {
        return $this->belongsTo(Tag::class, 'beacon_id', 'beacon_id')->withTrashed();
    }

    /**
     * Get the user that resolves the alert
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id')->withTrashed();
    }

    /**
     * Convert occured_at to Asia/Kuala_Lumpur timezone.
     *
     * @return string
     */
    public function getOccuredAtTzAttribute()
    {
        $date = Carbon::createFromFormat('Y-m-d H:i:s', $this->occured_at, 'UTC');
        $date->setTimezone('Asia/Kuala_Lumpur');
        return $date->format('Y-m-d g:i:s A');
    }

    /**
     * Convert occured_at to Asia/Kuala_Lumpur timezone, return time only.
     *
     * @return string
     */
    public function getOccuredAtTimeTzAttribute()
    {
        $date = $this->occured_at_tz;
        $string = explode(' ', $date);
        //return $string;
        return $string[1].' '.$string[2];
    }

    /**
     * Convert resolved_at to Asia/Kuala_Lumpur timezone.
     *
     * @return string
     */
    public function getResolvedAtTzAttribute()
    {
        if(isset($this->resolved_at)){
            $date = Carbon::createFromFormat('Y-m-d H:i:s', $this->resolved_at, 'UTC');
            $date->setTimezone('Asia/Kuala_Lumpur');
            return $date->format('Y-m-d H:i:s');
        } else {
            return "-";
        }
    }

    /**
     * Convert occured_at to Asia/Kuala_Lumpur timezone.
     *
     * @return string
     */
    public function getTimeDiffTzAttribute()
    {
        $date = Carbon::createFromFormat('Y-m-d H:i:s', $this->occured_at, 'UTC');
        $date->setTimezone('Asia/Kuala_Lumpur');
        $today = Carbon::now('Asia/Kuala_Lumpur')->setTimeZone('UTC');

        $diff = $date->diff($today)->format('%H:%i:%s');
        $hour = explode(':', $diff)[0];
        $minute = explode(':', $diff)[1];
        $second = explode(':', $diff)[2];

        if($hour == '00' && $minute == '00'){
            return 'Now';
        }

        if ($hour == '00'){
            $hour = '';
        } else {
            if($hour[0] == '0'){
                $hour = $hour[1];
            }
            $hour = $hour.'hrs ';
        }

        if ($minute == '00'){
            $minute = '';
        } else {
            if($minute[0] == '0'){
                $minute = $minute[1].'ms';
            } 
            $minute = $minute.'ms ';
        }

        return $hour.$minute.'ago';
    }
}
