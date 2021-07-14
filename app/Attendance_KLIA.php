<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
class Attendance_KLIA extends Model
{
    use HasFactory;
    protected $table = 'attendances_table';
    protected $fillable = [
        'id', 'tag_mac', 'gateway_id', 'first_seen', 'last_seen', 'date', 'duration',
    ];

  
    public function gateway(){
        return $this->belongsTo(Reader::class, 'gateway_id', 'gateway_id');
    }

    public function tag(){
        return $this->belongsTo(Tag::class, 'tag_mac', 'beacon_mac');
    }

    public function getFirstSeenTzAttribute()
    {
        $date = Carbon::createFromFormat('Y-m-d H:i:s', $this->first_seen, 'UTC');
        $date->setTimezone('Asia/Kuala_Lumpur');
        return $date->format('Y-m-d g:i:s A');
    }
    public function getLastSeenTzAttribute()
    {
        $date = Carbon::createFromFormat('Y-m-d H:i:s', $this->last_seen, 'UTC');
        $date->setTimezone('Asia/Kuala_Lumpur');
        return $date->format('Y-m-d g:i:s A');
    }
}
