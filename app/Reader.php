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
    protected $fillable = [
        'serial', 'uuid', 'mac_addr'
    ];
    
    /**
     * Get the floor records associated with the reader.
     */
	public function floor()
	{
		return $this->belongsTo(Floor::class);
    }
}
