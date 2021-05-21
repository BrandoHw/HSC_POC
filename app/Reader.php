<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

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
        return $this->belongsTo(Location::class, 'location_id', 'location_master_id')->withTrashed();
    }

    /**
     * Get the tag's current location.
     *
     * @return string
     */
    public function getLocationFullAttribute()
    {
        $location = $this->location;
        return $location->floor."F - ".$location->location_description;
    }
}
