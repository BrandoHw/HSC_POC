<?php

namespace App\Services;
use Carbon\Carbon;
use App\Building;
use App\Floor;
use App\Reader;
use App\Timeblocks;

class CalendarService
{
    public function generateCalendarData($weekDays, $id)
    {
        $calendarData = [];

        /* Create time range from 8AM to 6PM, start_time & end_time can be configured in config\app */
        $timeRange = (new TimeService)->generateTimeRange(config('app.calendar.start_time'), config('app.calendar.end_time'));

        $timeblocks  = Timeblocks::where('schedule_id', $id)
            ->get();
        $i = 0;
        foreach ($timeRange as $time) /* $time = ['start' => '8:00:00', 'end' => '8:30:00'] */
        {
            $start = Carbon::parse($time['start']);
            $end = Carbon::parse($time['end']);
            $timeText = $start->format('H:i') . ' - ' . $end->format('H:i'); /* $timeText = '8:00-8:30' */
            $calendarData[$timeText] = []; /* calendarData = ['8:00-8:30' => []] */

            foreach ($weekDays as $index => $day) /* $index = '1', $day = 'Mon' */
            {
                $timeblock = $timeblocks
                    ->where('day', $index)
                    ->where('start_time', $time['start'])
                    ->first();

                if ($timeblock)
                {
                    array_push($calendarData[$timeText], [
                        'id' => $timeblock->id,
                        'rowspan'    => $timeblock->difference/30 ?? '',
                        'day' => $index,
                        'start_time' => $time['start'],
                        'end_time' => $timeblock->end_time,
                        'schedule_id' => $id,
                        'company' => $timeblock->company,
                        'building' => $timeblock->building,
                        'i' => $i++
                    ]);
                }
                else if (!$timeblocks->where('day', $index)->where('start_time', '<', $time['start'])->where('end_time', '>=', $time['end'])->count())
                {
                    array_push($calendarData[$timeText], [
                        'day' => $index,
                        'schedule_id' => $id,
                        'start_time' => $time['start'],
                        'end_time' => $time['end'],
                        'i' => $i++
                    ]);
                }
                else
                {
                    array_push($calendarData[$timeText], 0);
                }
            }
        }
    
        return $calendarData;
    }

   function console_log( $data ){
        echo '<script>';
        echo 'console.log('. json_encode( $data ) .')';
        echo '</script>';
      }
}
