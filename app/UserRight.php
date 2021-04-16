<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserRight extends Model
{
    use SoftDeletes;
     
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'users_right_table';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'user_right_id';

     /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'beacon_mac', 'description'
    ];

    /**
     * Get the user record associated with the userRight
     */
    public function user()
    {
        return $this->hasMany(User::class, 'right_id', 'user_right_id')->withTrashed();
    }
}
