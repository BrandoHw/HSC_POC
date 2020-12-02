<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'detail', 'start_date', 'end_date'
    ];
    
    /**
     * Get the groups that belong to the project
     */
	public function groups()
	{
		return $this->belongsToMany(Group::class);
	}
}
