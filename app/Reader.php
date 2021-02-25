<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Reader extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    // protected $fillable = [
    //     'serial', 'mac_addr'
    // ];
    protected $table = 'gateways_table2';
    protected $fillable = [
        'serial', 'mac_address', 'reader_ip', 'location_id', 'reader_status', 'up_status', 'assigned'
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
}
