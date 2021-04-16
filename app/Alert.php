<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Alert extends Model
{
    use SoftDeletes;
    
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'alerts_table';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'alert_id';

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
        'occured_at', 'resolved_at', 'action'
    ];

    /**
     * Get the reader where the alert occurs
     */
    public function reader()
    {
        return $this->belongsTo(Reader::class, 'reader_id', 'gateway_id')->withTrashed();
    }

    /**
     * Get the policy that triggers the alert
     */
    public function policy()
    {
        return $this->belongsTo(Policy::class, 'rules_id', 'rules_id')->withTrashed();
    }

    /**
     * Get the tag that triggers the alert
     */
    public function tag()
    {
        return $this->belongsTo(Tag::class, 'beacon_id', 'beacon_id')->withTrashed();
    }

    /**
     * Get the user that resolves the alert
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id')->withTrashed();
    }
}
