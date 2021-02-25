<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Reader extends Model
{

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'gateways_table';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'gateway_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'mac_addr', 'reader_ip', 'reader_status', 'up_status', 'assigned', 'serial'
    ];
    
    /**
     * Get the floor records associated with the reader.
     */
	public function floor()
	{
		return $this->belongsTo(Floor::class);
    }

}
