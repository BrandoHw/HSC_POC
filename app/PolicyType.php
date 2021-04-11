<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PolicyType extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'rules_type_table';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'rules_type_id';

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
        'rules_type_desc'
    ];

    /**
     * Get the policy records associated with the policyType
     */
    public function policies()
    {
        return $this->hasMany(Policy::class, 'rules_type_id', 'rules_type_id');
    }
}
