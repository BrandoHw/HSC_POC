<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Resident extends Model
{
    use HasFactory;
    use SoftDeletes;
  
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
        'beacon_id', 
        'resident_fName', 'resident_lName', 'resident_age', 
        'gender', 'wheelchair', 'walking_cane',
        'x_value', 'y_value', 'z_value'
    ];
    /**
     * Get the tag that owns the resident
     */
    public function tag()
    {
        return $this->belongsTo(Tag::class, 'beacon_id', 'beacon_id')->withTrashed();
    }

    public function beacon()
    {
        return $this->belongsTo(Tag::class, 'beacon_id', 'beacon_id')->withTrashed();
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
