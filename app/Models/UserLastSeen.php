<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserLastSeen extends Model
{
    use HasFactory;
    protected $table = 'users_last_seen';
    protected $fillable = [
        'user_id', 
        'tag_mac', 
        'reader_mac',
        'rssi'
    ];

    public function location()
    {
        return $this->belongsTo('App\Models\Reader', 'reader_mac');
    }

    public function tag()
    {
        return $this->belongsTo('App\Models\Tag', 'reader_mac');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\Reader', 'reader_mac');
    }

}
