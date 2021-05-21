<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TagData extends Model
{
    use HasFactory;
    protected $table = 'tag_data';
    const UPDATED_AT = null;
    protected $fillable = [
        'id', 'mac_addr', 'rssi', 'gateway_mac', 'created_at',
    ];
}
