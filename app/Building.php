<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Building extends Model
{
  /**
   * The table associated with the model.
   *
   * @var string
   */
  protected $table = 'buildings_table';

  /**
   * The primary key associated with the table.
   *
   * @var string
   */
  protected $primaryKey = 'building_id';

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
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
