<?php

namespace App\Services;
use Carbon\Carbon;
use App\Reader;
use App\Timeblocks;
use App\Schedule;
use App\User;
use App\Tag;
use App\TagData;
use App\TagDataLog;
use Cron\DayOfWeekField;

class AttendanceService
{
    public function generateAttendanceData($groups, $date_start, $date_end)
    {       
        //Generate array of group ids   
        $group_ids = array();
        foreach ($groups as $group){
            //$this->console_log($group);
            $group_ids[$group->id] = $group;
        }
        //Generate array of schedule ids   
        $schedule_ids = array();
        foreach ($groups as $key => $group){
            $schedule_ids[$key] = $group->schedule->id;
            // $this->console_log($schedule_ids);
        }
   
        //Get timeblocks for schedule(s)
        $timeblocks = Timeblocks::whereIn('schedule_id', $schedule_ids)->orderBy('start_time', 'asc')->get();  

        //Sort timeblocks into a 3-dimensional array
        //Array rows are the schedule id
        //Array columns are the day
        //Elements contain array of timeblocks for given schedule on a given day.
        $timeblocks_schedules = array();
        foreach ($timeblocks as $timeblock){
            // $readers_mac = array();
            // foreach($timeblock->building->readers as $reader){
            //     array_push($readers_mac, $reader->mac_addr);
            // }
            // $timeblock->push('readers_mac', $readers_mac);

            if (isset($timeblocks_schedules[$timeblock->schedule_id])){
                array_push($timeblocks_schedules[$timeblock->schedule_id][$timeblock->day], $timeblock);
            }else{
                $timeblocks_schedules[$timeblock->schedule_id] = array(1 => array(), 2 => array(),
                 3 => array(), 4 => array(), 5 => array(), 6 => array(), 7 => array());
                array_push($timeblocks_schedules[$timeblock->schedule_id][$timeblock->day], $timeblock);
            }
        }
        //$this->console_log($timeblocks_schedules);

        //Generate array of all user ids from the selected groups
        $users = User::whereIn('group_id', array_keys($group_ids))->get();
        $user_ids = array();
        foreach($users as $user){
            $user_ids[$user->id] = $user;
        }
        //$this->console_log($user_ids);
        //Generate array of tag mac addressess, KEY = Mac addr, VALUE = user id
        $tags = Tag::whereIn('user_id', array_keys($user_ids))->get();
        //$this->console_log($tags);
        $tag_macs = array();
        foreach($tags as $tag){
            $tag_macs[$tag->mac_addr] = $tag->user_id;
        }

        //$this->console_log($tag_macs);
        //Get all tag data that matches the mac address that was collected between $date_start and $date_end
        $tag_data = TagData::whereIn('mac_addr', array_keys($tag_macs))
                            ->where('created_at', '>=', $date_start)
                            ->where('created_at', '<=', $date_end)
                            ->orderBy('created_at', 'asc')
                            ->get();
        //$this->console_log($tag_data);

        //Create array of array of tag data logs
        //First Array is sorted by mac address
        //Second Array is sorted by date
        $tag_data_logs = TagDataLog::whereIn('mac_addr', array_keys($tag_macs))
                                    ->where('uploaded_at', '>=', $date_start)
                                    ->where('uploaded_at', '<=', $date_end)
                                    ->orderBy('uploaded_at', 'asc')
                                    ->get();

        $tag_data_logs_array = array();
        foreach($tag_data_logs as $tag_data_log){
            if (isset($tag_data_logs_array[$tag_data_log->mac_addr])){
                $tag_data_logs_array[$tag_data_log->mac_addr][$tag_data_log->uploaded_at] = $tag_data_log;
            }else{
                $tag_data_logs_array[$tag_data_log->mac_addr] = array();
                $tag_data_logs_array[$tag_data_log->mac_addr][$tag_data_log->uploaded_at] = $tag_data_log;
            }
        }

        //$this->console_log($tag_data_logs_array);
        //Create and array of arrays. Each array has a key that corresponds to the user id for that tag data
        //e.g [14 => [Tag Data Array for user ID 14], 15=> [Tag Data Array for user id 15]]
        $tag_array = array();
        foreach($tag_data as $single_tag_data){
            if (isset($tag_array[$tag_macs[$single_tag_data->mac_addr]])){
                array_push($tag_array[$tag_macs[$single_tag_data->mac_addr]], $single_tag_data);
            }else{
                $tag_array[$tag_macs[$single_tag_data->mac_addr]] = array();
                array_push($tag_array[$tag_macs[$single_tag_data->mac_addr]], $single_tag_data);
            }
        }
    
        //Get All Readers
        $readers = Reader::all();
        $reader_macs = array();
        foreach ($readers as $reader){
            $reader_macs[$reader->id] = $reader->mac_addr;
        };

        //$this->console_log($tag_array);
        //$tag_data = TagData// Create tag data
        foreach ($tag_array as $key => $tag_data_for_id){
            //Convert user to schedule id
            $schedule_id = $group_ids[$user_ids[$key]->group_id]->schedule->id;
            //$this->console_log($schedule_id);
            //Get all timeblocks for schedule
            // $this->console_log($schedule_id);
            // $this->console_log($timeblocks);
            // $this->console_log($timeblocks_schedules);
            $timeblocks = $timeblocks_schedules[$schedule_id];

            //$this->console_log($timeblocks);
            $start_times = array();
            $end_times = array();
            $start_locations = array();
            $end_locations = array();
            for ($i = 1; $i <= 7; $i++){
                $start_time = Carbon::now()->startOFDay()->format('H:i:s');
                $end_time = Carbon::now()->endOFDay()->format('H:i:s');
                if (empty($timeblocks[$i])){
                    $start_times[$i] = $start_time; 
                    $end_times[$i] = $end_time; 
                    $start_locations[$i] = null; 
                    $end_locations[$i] = null; 
                }else{
                    // $this->console_log($reader_macs);
                    // $this->console_log($timeblocks[$i]);
                    $start_times[$i] = reset($timeblocks[$i])->start_time; 
                    $end_times[$i] = end($timeblocks[$i])->end_time; 
                    
                    $start_location_readers = array();
                    foreach(reset($timeblocks[$i])->building->readers as $reader){
                        array_push($start_location_readers, $reader->mac_addr);
                    }
                    $start_locations[$i] = $start_location_readers; 
                    
                    $end_location_readers = array();
                    foreach(end($timeblocks[$i])->building->readers as $reader){
                        array_push($end_location_readers, $reader->mac_addr);
                    }
                    $end_locations[$i] = $end_location_readers; 
                }
            };
            // $this->console_log($start_locations);
            // $this->console_log($end_locations);
         
            $tag_data_logs_push = array(); //Array of tag data logs to push to the database
            $tag_duration_array = array(); //Array of tag durations
            foreach ($tag_data_for_id as $tag_data){
                $carbon = new Carbon($tag_data->created_at);
                $day = $carbon->dayOfWeek;
                if($day == 0){$day = 7;} //In carbon 0 = Sunday, in our database 7 = Sunday
                        
                $date = new Carbon($tag_data->created_at);
                $date = $date->format('Y-m-d'); //Format
                $reader_mac = $tag_data->reader_mac;
                //$this->console_log($tag_data);
                $tag_data_log_for_mac_date = $tag_data_logs_array[$tag_data->mac_addr][$date];

                //Increment duration each count = 5 minutes 
                if (isset($tag_duration_array[$date]))
                    {$tag_duration_array[$date]= $tag_duration_array[$date] + 1;}
                else
                    {$tag_duration_array[$date]= 1;};


                //Check Location, If location correct check start and end times
                 /*Attendance State is used to keep track of check-in/check-out status of the user
                    0 = Not Checked-in or out
                    1 = Checked-in correctly 
                    2 = Checked-out correctly
                    3 = Check-in and Out correctly
                    */
                //  $this->console_log($start_locations[$day]);
                //  $this->console_log($end_locations[$day]);
                //  $this->console_log($reader_mac);
              
                
                if ($this->isWithinXminutes($carbon, $start_times[$day], 5)){
                    if (in_array($reader_mac, $start_locations[$day])){
                        if ($tag_data_log_for_mac_date->attendance_state == 2 || $tag_data_log_for_mac_date->attendance_state == 3){
                            $tag_data_log_for_mac_date->attendance_state = 3;
                        }
                        else{
                            $tag_data_log_for_mac_date->attendance_state = 1;
                        }
                    }
                }
                elseif ($this->isWithinXminutes($carbon, $end_times[$day], 5)){
                    if (in_array($reader_mac, $end_locations[$day])){
                        if ($tag_data_log_for_mac_date->attendance_state == 1 || $tag_data_log_for_mac_date->attendance_state == 3){
                            $tag_data_log_for_mac_date->attendance_state = 3;
                        }
                        else{
                            $tag_data_log_for_mac_date->attendance_state = 2;
                        }
                    }
               } 
                else{
                    if ($tag_data_log_for_mac_date->attendance_state == null){
                            $tag_data_log_for_mac_date->attendance_state = 0;
                    }
                }
                $tag_data_log_for_mac_date->duration = $tag_duration_array[$date];
                $tag_data_logs_array[$tag_data->mac_addr][$date] = $tag_data_log_for_mac_date;
            }
        }
        
        //$this->console_log($tag_data_log_for_mac_date);

        //LOGIC HERE

    
        //set all 
        //update
        foreach ($tag_data_logs_array as $tag_data_log_mac){
            foreach ($tag_data_log_mac as $tag_data_log){
                array_push($tag_data_logs_push, $tag_data_log);
            }
        };

        // return in table form
        //$this->console_log($tag_data_logs_push);

        if (empty($tag_data_logs_push)){
            return [];
        }else{
            foreach ($tag_data_logs_push as $tag_data_log){
                // $this->console_log($tag_data_log);
                $tag_data_log->save();
            }
            return $tag_data_logs_push;
        }
            
    }

    function isWithinXminutes($created_at, $time, $x){
        $carbon_created = new Carbon($created_at);
        $carbon_start = new Carbon($time);
        $carbon_end = new Carbon($time);
        $carbon_start = $carbon_start->setYear($carbon_created->year)
                                    ->setMonth($carbon_created->month)
                                    ->setDay($carbon_created->day)
                                    ->subMinute($x);
        $carbon_end = $carbon_end->setYear($carbon_created->year)
                                ->setMonth($carbon_created->month)
                                ->setDay($carbon_created->day)
                                ->addMinute($x);

        if ($carbon_created->between($carbon_start, $carbon_end)){
            return true;
        }else{
            return false;
        }
    }
   function console_log( $data ){
        echo '<script>';
        echo 'console.log('. json_encode( $data ) .')';
        echo '</script>';
      }
}
