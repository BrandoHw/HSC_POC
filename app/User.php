<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use Notifiable;
    use HasRoles;
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'users_table';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = "user_id";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'fName', 'lName', 'phone_number', 'email',
        'username', 'password', 'gender'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['password'];
    
    /**
     * Get the userRight that owns the user
     */
    public function userRight()
    {
        return $this->belongsTo(userRight::class, 'right_id', 'user_right_id')->withTrashed();
    }

    /**
     * Get the userType that owns the user
     */
    public function userType()
    {
        return $this->belongsTo(userType::class, 'type_id', 'user_type_id')->withTrashed();
    }

    /**
     * Get the tag that owns the user
     */
    public function tag()
    {
        return $this->belongsTo(Tag::class, 'beacon_id', 'beacon_id')->withTrashed();
    }

      /**
     * Get the record of the last time the user was seen
     */
    public function lastSeen()
    {
        return $this->hasOne(UserLastSeen::class);
    }

    /**
     * Get the user's full name.
     *
     * @return string
     */
    public function getFullNameAttribute()
    {
        return ucfirst($this->fName)." ".ucfirst($this->lName);
    }
    
}
