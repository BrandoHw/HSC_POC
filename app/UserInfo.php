<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserInfo extends Model
{
    protected $table = "users_table";
    use HasFactory;
    protected $fillable = [
        'beacon_id', 'type_id', 'right_id', 'fName', 'lName', 'phone_number',
    ];
    
    // public function type()
    // {
    //     return $this->belongsTo(UserType::class);
    // }

    // public function right()
    // {
    //     return $this->belongsTo(UserRight::class);
    // }

    public function beacon()
    {
        return $this->belongsTo(Tag::class, 'beacon_id', 'beacon_id');
    }

 
}
