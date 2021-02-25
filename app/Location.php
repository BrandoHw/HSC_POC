<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use HasFactory;
    protected $table ='locations_master_table2';
    protected $fillable = [
        'location_type_id', 'location_description', 'floor'
    ];
    public $timestamps = false;
    public function floor_level()
	{
		return $this->belongsTo(Floor::class, 'floor', 'id');
    }

	public function type()
	{
		return $this->belongsTo(LocationType::class, 'location_type_id', 'type_id');
    }


}

