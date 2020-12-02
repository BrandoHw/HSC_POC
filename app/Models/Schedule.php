<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    /**
     * Get the timeblocks records associated with the schedule.
     */
	public function timeblocks()
	{
		return $this->hasMany(timeblocks::class);
    }

    /**
     * Get the groups records associated with the schedule.
     */
	public function group()
	{
		return $this->belongsTo(Group::class);
    }
    
    /**
     * Get the buildings records associated with the schedule.
     */
	public function buildings()
	{
		return $this->belongsToMany(Building::class);
	}
}
