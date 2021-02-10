<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Floor extends Model
{
     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
          'number', 'alias', 'building_id'
     ];

    /**
     * Get the buildings records associated with the floor.
     */
	public function building()
	{
		return $this->belongsTo(Building::class);
    }

    /**
     * Get the readers records associated with the floor.
     */
	public function readers()
	{
		return $this->hasMany(Reader::class);
     }
     
      /**
     * Get the readers records associated with the floor.
     */
	public function map()
	{
		return $this->hasOne(MapFile::class);
	}
}
