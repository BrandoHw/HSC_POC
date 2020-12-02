<?php

namespace App\Models;

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

    /**
     * Get the tag_data_logs that owns the tag
     */
    public function tagDataLogs()
    {
        return $this->hasMany(TagDataLog::class, 'mac_addr', 'mac_addr');
    }

}
