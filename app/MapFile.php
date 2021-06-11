<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MapFile extends Model
{
    use HasFactory;
    use SoftDeletes;
     
     //
     protected $fillable = [
        'name', 'url', 'floor_id'
    ];

    /**
     * Get the buildings records associated with the floor.
    */
	public function floor()
	{
		return $this->belongsTo(Floor::class, 'floor_id', 'floor_id');
    }

}
