<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LocationType extends Model
{
    use HasFactory;
    protected $table = 'locations_type_table';
    public $timestamps = false;
    protected $fillable = [
        'type_id', 'location_type'
    ];

    public function locations(){
        return $this->hasMany(Location::class);
    }
}
