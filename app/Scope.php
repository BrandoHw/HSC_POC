<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Scope extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'scopes_table';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'scope_id';

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
        'gateway_dwell_time', 'days', 'start_time', 'duration'
    ];

    /**
     * Get the policy records associated with the scope
     */
    public function policies()
    {
        return $this->hasMany(Policy::class, 'scope_id', 'scope_id');
    }
}
