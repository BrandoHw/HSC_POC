<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Scope extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'scopes_table';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'scope_id';

     /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'gateway_dwell_time', 'days', 'start_time', 'duration', 'target_type'
    ];

    /**
     * Get the policy records associated with the scope
     */
    public function policy()
    {
        return $this->hasOne(Policy::class, 'scope_id', 'scope_id');
    }

    /**
     * Get the tags associated with the scope
     */
    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'scope_beacons_table', 'scope_id', 'beacon_id');
    }

    /**
     * Get the locations associated with the scope
     */
    public function locations()
    {
        return $this->belongsToMany(Location::class, 'scope_locations_master_table', 'scope_id', 'location_id');
    }
}
