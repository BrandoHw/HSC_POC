<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Resident extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'residents_table';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'resident_id';

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
        'resident_fName', 'resident_lName', 'residnet_age',
        'wheelchair', 'walking_cane',
        'x_value', 'y_value', 'z_value'
    ];

    /**
     * Get the tag that owns the resident
     */
    public function tag()
    {
        return $this->belongsTo(Tag::class, 'beacon_id', 'beacon_id');
    }

    /**
     * Get the resident's full name.
     *
     * @return string
     */
    public function getFullNameAttribute()
    {
        return ucfirst($this->resident_fName)." ".ucfirst($this->resident_lName);
    }
}
