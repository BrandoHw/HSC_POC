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
        return $this->hasOne(User::class, 'beacon_id', 'beacon_id');
    }

    /**
     * Get the resident record associated with the tag
     */

    public function staff()
    {
        return $this->hasOne(UserInfo::class, 'beacon_id', 'beacon_id');
    }

    public function resident()
    {
        return $this->hasOne(Resident::class, 'beacon_id', 'beacon_id');
    }

    public function gateway(){
        return $this->hasOne(Reader::class, 'gateway_id', 'current_loc');
    }

}
