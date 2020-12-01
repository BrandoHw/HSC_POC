<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TagData extends Model
{
    //
    public $timestamps = false;
    protected $fillable = [
        'mac_addr', 'uuid', 'rssi', 'reader_mac', 'created_at'
    ];

    /**
     * Get the tag that owns this data
     */
    public function tag()
    {
        return $this->belongsTo(Tag::class);
    }
}
