<?php

namespace App\Services;

class TagTargetService
{
    public function generateTagTarget($residents, $users, $current)
    {
        $targetsNull = [];

        if(isset($current)){
            if(isset($current->user_id)){
                $users = $users->push($current)->sortBy('fName');
            } else {
                $residents = $residents->push($current)->sortBy('resident_fName');
            }
        }
        
        foreach($residents->sortBy('resident_fName') as $resident){
            $val = 'R-'.$resident->resident_id;
            $name = 'R'.$resident->resident_id.' - '.$resident->full_name;
            $targetsNull[$val] = $name;
        }

        foreach($users->sortBy('fName') as $user){
            $val = 'U-'.$user->user_id;
            $name = 'U'.$user->user_id.' - '.$user->full_name;
            $targetsNull[$val] = $name;
        }

        return $targetsNull;
    }
}