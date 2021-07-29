<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use App\UserType;
use App\UserRight;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\HasApiTokens;
class User extends Authenticatable
{
    use Notifiable;
    use HasRoles;
    use SoftDeletes;
    use HasApiTokens;

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
        'username', 'password', 'gender',
        'image_url',
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
        return $this->belongsTo(userRight::class, 'right_id', 'user_right_id');
    }

    /**
     * Get the userType that owns the user
     */
    public function userType()
    {
        return $this->belongsTo(userType::class, 'type_id', 'user_type_id');
    }

    /**
     * Get the tag that owns the user
     */
    public function tag()
    {
        return $this->belongsTo(Tag::class, 'beacon_id', 'beacon_id');
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

    public function getThumbnail(){
        return Storage::disk('s3-resized')->url("resized-".$this->image_url);
    }
    
}
