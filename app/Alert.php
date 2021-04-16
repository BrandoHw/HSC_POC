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
        return $this->belongsTo(Reader::class, 'reader_id', 'gateway_id')->withTrashed();
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
     * Convert resolved_at to Asia/Kuala_Lumpur timezone.
     *
     * @return string
     */
    public function getResolvedAtTzAttribute()
    {
        if(isset($this->resolved_at)){
            $date = Carbon::createFromFormat('Y-m-d H:i:s', $this->resolved_at, 'UTC');
            $date->setTimezone('Asia/Kuala_Lumpur');
            return $date->format('Y-m-d g:i:s A');
        } else {
            return "-";
        }
    }
}
