<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserType extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'users_type_table';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'user_type_id';

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
        'type', 'description'
    ];

    /**
     * Get the user record associated with the userType
     */
    public function user()
    {
        return $this->hasMany(User::class, 'type_id', 'user_type_id');
    }

}
