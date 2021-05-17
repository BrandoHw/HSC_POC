<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tag extends Model
{
    use SoftDeletes;
     
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
    protected $primaryKey = 'beacon_id';

   /**
     * The storage format of the model's date columns.
     *
     * @var string
     */
    protected $dateFormat = 'Y-m-d H:i:s';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'beacon_type', 'beacon_mac', 'reader_mac', 'current_loc'
    ];
 
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
        return $this->hasOne(User::class, 'beacon_id', 'beacon_id')->withTrashed();
    }

    /**
     * Get the resident record associated with the tag
     */

    public function staff()
    {
        return $this->hasOne(User::class, 'beacon_id', 'beacon_id')->withTrashed();
    }

    public function resident()
    {
        return $this->hasOne(Resident::class, 'beacon_id', 'beacon_id')->withTrashed();
    }

    public function gateway(){
        return $this->hasOne(Reader::class, 'gateway_id', 'current_loc')->withTrashed();
    }

    /**
     * Get the scopes associated with the tag
     */
    public function scopes()
    {
        return $this->belongsToMany(Scope::class, 'scope_beacons_table', 'beacon_id', 'scope_id');
    }

    /**
     * Get the tag's current location.
     *
     * @return string
     */
    public function getCurrentLocationAttribute()
    {
        $location = $this->gateway->location;
        if(isset($location)){
            return $location->floor."F - ".$location->location_description;
        } else {
            return "-";
        }
    }

    /**
     * Check whether tag is assigned.
     *
     * @return string
     */
    public function getIsAssignedAttribute()
    {
        if($this->beacon_type == 1){
            if(isset($this->resident->full_name)){
                return true;
            } else {
                return false;
            }
        } else {
            if(isset($this->user->full_name)){
                return true;
            } else {
                return false;
            }
        }
    }
}
