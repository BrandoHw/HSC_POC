<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Resident extends Model
{
    use HasFactory;
    protected $table = "residents_table";
    protected $fillable = [
        'beacon_id', 'resident_fName', 'resident_lName', 'resident_age', 'wheelchair', 'walking_cane', 'x_value', 'y_value', 'z_value'
    ];

    public $timestamps = false;

    public function beacon()
    {
        return $this->belongsTo(Tag::class, 'beacon_id', 'beacon_id');
    }

}
