<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GatewayZone extends Model
{
    use HasFactory;
    protected $fillable = [
        'geoJson', 'mac_addr', 'location', 'image_id'
    ];

}
