<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TagType extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'beacons_type_table';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'beacon_type_id';


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
    protected $fillable = ['beacon_type_desc'];

    /**
     * Get the tags associated with the tagType
     */
    public function tags()
    {
        return $this->hasMany(Tag::class, 'beacon_type', 'beacon_type_id');
    }

}
