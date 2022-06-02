<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    use HasFactory;
      /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'activity_log_table';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = "log_id";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'log_id', 'log_time', 'gateway_id', 'beacon_id',
        'rssi', 'battery_level',
        'x_value', 'y_value', 'z_value',
        'rawData',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['password'];

    /**
     * Get the beacons associated with the log
     */
    public function beacon()
    {
        return $this->belongsTo(Tag::class, 'beacon_id', 'beacon_id');
    }

        /**
     * Get the gateway associated with the log
     */
    public function gateway()
    {
        return $this->belongsTo(Reader::class, 'gateway_id', 'gateway_id');
    }


}
