<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Building extends Model
{
  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */

  // protected $table ='buildings_table';
  protected $fillable = [
      'name', 'detail', 'floor_num', 'address', 'lat', 'lng',
  ];

  /**
   * Get the floors records associated with the building.
   */
  public function floors()
  {
  return $this->hasMany(Floor::class);
  }

  /**
   * Get the readers records associated with the building.
   */
  public function readers()
  {
  return $this->hasManyThrough(Reader::class, Floor::class);
  }

}
