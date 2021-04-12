<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Policy extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'rules_table';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'rules_id';

      /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'description',
        'x_threshold', 'y_threshold', 'z_threshold',
        'x_frequency', 'y_frequency', 'z_frequency',
        'alert_action', 'attendance', 'geofence',
    ];

    /**
     * Get the policyType that owns the policy
     */
    public function policyType()
    {
        return $this->belongsTo(policyType::class, 'rules_type_id', 'rules_type_id');
    }

    /**
     * Get the scope that owns the policy
     */
    public function scope()
    {
        return $this->belongsTo(Scope::class, 'scope_id', 'scope_id');
    }
}
