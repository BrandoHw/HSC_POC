<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'date', 'clock_in', 'clock_out', 'total_time_spent'
    ];
}