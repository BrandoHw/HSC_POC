<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Color extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'colors_table';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'color_id';

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
        'color_code', 'color_name'
    ];

    /**
     * Get the role records associated with the color
     */
    public function role()
    {
        return $this->hasOne(Role::class, 'color_id', 'color_id');
    }

    /**
     * Get the color's code and name.
     *
     * @return string
     */
    public function getCodeAndNameAttribute()
    {
        return $this->color_code.'-'.$this->color_name;
    }
}
