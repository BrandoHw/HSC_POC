<?php

namespace App\Services;

class AttendanceStatusService
{
    public function check_attendance_status($status)
    {
        switch($status){
            case 0:
                return '<span class="text-success">On-Time</span>';
                break;
            case 1:
                return '<span class="text-danger">Late</span>';
                break;
            case -1:
                return '<span class="text-primary">Early</span>';
                break;
            case -5:
                return '<span class="text-warning">Wrong Location</span>';
                break;
        }
    }
}
