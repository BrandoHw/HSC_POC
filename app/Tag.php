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
    protected $table = "beacons_table";
    // protected $fillable = [
    //     'serial', 'uuid', 'mac_addr'
    // ];
    protected $fillable = [
        'beacon_type', 'beacon_mac', 'reader_mac', 'current_loc'
    ];
    /**
     * Get the user that owns the tag
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

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

    // public function staff()
    // {
    //     return $this->hasOne(Staff Class)
    // }

    // public function patient()
    // {
    //     return $this->hasOne(Patient Class)
    // }

    // public function type(){
    //     return $this->hasOne(Beacon Type)
    // }

}
