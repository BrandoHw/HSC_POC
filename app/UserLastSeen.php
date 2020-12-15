<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserLastSeen extends Model
{
    //Most recently seen user data
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
        return $this->belongsTo('App\Reader', 'reader_mac');
    }

    public function tag()
    {
        return $this->belongsTo('App\Tag', 'tag_mac');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

}
