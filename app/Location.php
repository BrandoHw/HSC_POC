<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Location extends Model
{
    use HasFactory;
    use SoftDeletes;
     
    protected $table ='locations_master_table';
    protected $fillable = [
        'location_type_id', 'location_description', 'floor'
    ];
    protected $primaryKey = 'location_master_id';
    public $timestamps = false;
    public function floor_level()
	{
		return $this->belongsTo(Floor::class, 'floor', 'floor_id');
    }

	public function type()
	{
		return $this->belongsTo(LocationType::class, 'location_type_id', 'type_id');
    }

    /**
     * Get the scopes associated with the location
     */
    public function scopes()
    {
        return $this->belongsToMany(Scope::class, 'scope_locations_master_table', 'location_id', 'scope_id');
    }

    /**
     * Get the scopes associated with the location
     */
    public function residents()
    {
        return $this->hasMany(Residents::class, 'residents_table', 'location_room_id', 'location_master_id');
    }


}

