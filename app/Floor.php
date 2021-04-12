<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Floor extends Model
{
     /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'floors_table';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'floor_id';

     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
//     protected $table ='floors_table';
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
     
       /**
     * Get the locations associated with the floor.
     */
	public function locations()
	{
		return $this->hasMany(Location::class);
	}
}
