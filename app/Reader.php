<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
class Reader extends Model
{
    //use SoftDeletes;
    
    /**
     * The table associated with the model.
     *
     * @var string
     */
    // protected $table = 'gateways_table';
    protected $table = 'gateways_table';
    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'gateway_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $fillable = [
        'serial', 'mac_addr', 'reader_ip', 'location_id', 'reader_status', 'up_status', 'down_status', 'assigned'
    ];
    /**
     * Get the floor records associated with the reader.
     */
	// public function floor()
	// {
	// 	return $this->belongsTo(Floor::class);
    // }

    /**
     * Get the location records associated with the reader.
     */
    public function location()
    {
        return $this->belongsTo(Location::class, 'location_id', 'location_master_id');
    }

    /**
     * Get the tag's current location.
     *
     * @return string
     */
    public function getLocationFullAttribute()
    {
        
        $location = $this->location;
        if ($location)
            return $location->floor_level->alias." - ".$location->location_description;
        else
            return null;
    }

    public function getLastSeenAttribute()
    {
        if ($this->serial === null)
            return "N/A";
        if ($this->up_status != null && $this->down_status != null){
            $up_timestamp = Carbon::createFromFormat("Y-m-d H:i:s", $this->up_status);
            $down_timestamp = Carbon::createFromFormat("Y-m-d H:i:s", $this->down_status);
            if ($down_timestamp > $up_timestamp){
                return $down_timestamp->diffForHumans();
            }else{
                return "N/A";
            }
        }else{
            return "N/A";
        }
    }

    public function getLastSeenSecondsAttribute()
    {
        if ($this->serial === null)
            return 0;
        if ($this->up_status != null && $this->down_status != null){
            $up_timestamp = Carbon::createFromFormat("Y-m-d H:i:s", $this->up_status);
            $down_timestamp = Carbon::createFromFormat("Y-m-d H:i:s", $this->down_status);
            if ($down_timestamp > $up_timestamp){
                return $down_timestamp->timestamp;
            }else{
                return 0;
            }
        }else{
            return 0;
        }
    }
}
