<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'detail'
    ];

    /**
     * Get the buildings records associated with the company.
     */
    public function buildings()
    {
        return $this->hasMany(Building::class);
    }

    /**
     * Get the timeblocks records associated with the company.
     */
    public function timeblocks()
    {
        return $this->hasMany(Timeblocks::class);
    }
}
