<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance_KLIA extends Model
{
    use HasFactory;
    use HasFactory;
    protected $table = 'attendances_table';
    protected $fillable = [
        'id', 'tag_mac', 'gateway_id', 'first_seen', 'last_seen', 'date', 'duration',
    ];
}
