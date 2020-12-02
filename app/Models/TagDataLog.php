<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TagDataLog extends Model
{
    /*Attendance State is used to keep track of check-in/check-out status of the user
     0 = Not Checked-in or out
     1 = Checked-in correctly
     2 = Checked-out correctly
     3 = Check-in and Out correctly
    */
    public $timestamps = false;
    protected $fillable = [
        'attendance_state'
    ];
      /**
     * Get the user that owns the tag
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function tag()
    {
        return $this->belongsTo(Tag::class, 'mac_addr', 'mac_addr');
    }
    
}
