<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'beacons_table';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['beacon_mac'];

    /**
     * Get the tagType that owns the tag
     */
    public function tagType()
    {
        return $this->belongsTo(TagType::class, 'beacon_type', 'beacon_type_id');
    }

    /**
     * Get the user record associated with the tag
     */
    public function user()
    {
        return $this->hasOne(User::class, 'beacon_id', 'id');
    }

    /**
     * Get the resident record associated with the tag
     */
    public function resident()
    {
        return $this->hasOne(Resident::class, 'beacon_id', 'id');
    }

}
