<?php

namespace App;

use Spatie\Permission\Models\Role as BaseRole;
use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends BaseRole
{
    use SoftDeletes;

    /**
     * Get the color that owns the role
     */
    public function color()
    {
        return $this->belongsTo(Color::class, 'color_id', 'color_id');
    }
}
