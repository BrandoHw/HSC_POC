<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Timeblocks extends Model
{
    //
    protected $fillable = [
        'start_time', 
        'end_time', 
        'day',
    ];
    public $timestamps = false;
    
    const WEEK_DAYS = [
        '1' => 'Sun',
        '2' => 'Mon',
        '3' => 'Tue',
        '4' => 'Wed',
        '5' => 'Thur',
        '6' => 'Fri',
        '7' => 'Sat',
    ];


    public function getDifferenceAttribute()
    {
        return Carbon::parse($this->end_time)->diffInMinutes($this->start_time);
    }

    public static function isTimeAvailable($weekday, $startTime, $endTime, $schedule_id, $timeblock)
    {
        $timeblocks = self::where('day', $weekday)
            ->when($timeblock, function ($query) use ($timeblock) {
                $query->where('id', '!=', $timeblock);
            })
            ->where(function ($query) use ($schedule_id) {
                $query->where('schedule_id', $schedule_id);
            })
            ->where([
                ['start_time', '<', $endTime],
                ['end_time', '>', $startTime],
            ])
            ->count();


        return !$timeblocks;
    }

    /**
     * Get the schedules records associated with the timeblock.
     */
	public function schedule()
	{
		return $this->belongsTo(Schedule::class);
    }
    
    /**
     * Get the building records associated with the timeblock.
     */
	public function building()
	{
		return $this->belongsTo(Building::class);
    }
    
    /**
     * Get the company records associated with the timeblock.
     */
	public function company()
	{
		return $this->belongsTo(Company::class);
	}

}
