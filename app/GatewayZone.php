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

    //
    protected $table ='gateway_zones2';

    public function gateway(){
        return $this->belongsTo(Reader::class, 'mac_addr', 'mac_addr');
    }


}
