<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'serial', 'uuid', 'mac_addr'
    ];

    /**
     * Get the user that owns the tag
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
