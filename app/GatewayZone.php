<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GatewayZone extends Model
{
    //use SoftDeletes; SoftDeletes will interfere with on delete triggers
    use HasFactory;
    
    protected $fillable = [
        'geoJson', 'mac_addr', 'location', 'image_id'
    ];

    //
    protected $table ='gateway_zones';

    public function gateway(){
        return $this->belongsTo(Reader::class, 'mac_addr', 'mac_addr');//->withTrashed();
    }


}
