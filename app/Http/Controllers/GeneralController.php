<?php

namespace App\Http\Controllers;

use App\Floor;
use App\MapFile;
use Carbon\Carbon;
use Facade\FlareClient\Stacktrace\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use App\GatewayZone;
use App\Alert;
use App\Resident;
use App\Tag;
use App\Policy;
class GeneralController extends Controller
{
    //

    public function index (){
        $request = [];

        $request['rule_id'] = 26;
        $request['date'] = -1;
        $request['num'] = 5;

        $policy = Policy::find($request['rule_id']) ?? null;
        $data_update = collect();

        if(!isset($policy)){
            $data = collect([
                'name' => '-',
                'type' => '-',
                'attendance' => '-',
                'curr_loc' => '-',
                'detected_at' => '-',
            ]);

            $data_update->push($data);
        } else {
            $date = $request['date'];
            $num = $request['num'];
            
            $now = Carbon::now();
            $today = Carbon::now('Asia/Kuala_Lumpur')->setTime(0,0,0)->setTimeZone('UTC');
            
            if($date == -1){
                $date_carbon = $today;
            } else {
                $date_carbon = Carbon::parse($request['date'], 'Asia/Kuala_Lumpur');
            }
    
            $start_time = Carbon::parse($policy->datetime_at_utc);
            $policy['absent'] = -1;
    
            if($num == -1){
                $targets = $policy->scope->tags;
            } else {
                $targets = $policy->scope->tags->sortByDesc('updated_at')->take($num); 
            }
    
            if($date_carbon >= $today){
                if($now > $start_time){
                    if($policy->attendance != 0){
                        $policy['absent'] = count($policy->all_targets) - ($policy->alerts->where('occured_at', '>=', date($policy->datetime_at_utc))->where('occured_at', '<', date('Y-m-d H:i:s', strtotime($policy->datetime_at_utc . ' +1 day')))->unique('beacon_id')->count());
                        foreach($targets as $target){
                            $target['found'] = $policy->alerts
                            ->where('beacon_id', $target->beacon_id)
                            ->where('occured_at', '>=', date($policy->datetime_at_utc))
                            ->where('occured_at', '<', date('Y-m-d H:i:s', strtotime($policy->datetime_at_utc . ' +1 day')))
                            ->first() ?? null;
                        }
                    } else {
                        $policy['absent'] = $policy->alerts->where('occured_at', '>=', date($policy->datetime_at_utc))->where('occured_at', '<', date('Y-m-d H:i:s', strtotime($policy->datetime_at_utc . ' +1 day')))->unique('beacon_id')->count();
                        foreach($targets as $target){
                            $target['found'] = $policy->alerts
                            ->where('beacon_id', $target->beacon_id)
                            ->where('occured_at', '>=', date($policy->datetime_at_utc))
                            ->where('occured_at', '<', date('Y-m-d H:i:s', strtotime($policy->datetime_at_utc . ' +1 day')))
                            ->last() ?? null;
                        }
                    }
                }
            } else {
                $today_check = false;
                if($policy->attendance != 0){
                    $policy['absent'] = count($policy->all_targets) - ($policy->alerts->where('occured_at', '>=', date($policy->datetime_at_utc))->where('occured_at', '<', date('Y-m-d H:i:s', strtotime($policy->datetime_at_utc . ' +1 day')))->unique('beacon_id')->count());
                    foreach($targets as $target){
                        $target['found'] = $policy->alerts
                        ->where('beacon_id', $target->beacon_id)
                        ->where('occured_at', '>=', date($policy->datetime_at_utc))
                        ->where('occured_at', '<', date('Y-m-d H:i:s', strtotime($policy->datetime_at_utc . ' +1 day')))
                        ->first() ?? null;
                    }
                } else {
                    $policy['absent'] = $policy->alerts->where('occured_at', '>=', date($policy->datetime_at_utc))->where('occured_at', '<', date('Y-m-d H:i:s', strtotime($policy->datetime_at_utc . ' +1 day')))->unique('beacon_id')->count();
                    foreach($targets as $target){
                        $target['found'] = $policy->alerts
                        ->where('beacon_id', $target->beacon_id)
                        ->where('occured_at', '>=', date($policy->datetime_at_utc))
                        ->where('occured_at', '<', date('Y-m-d H:i:s', strtotime($policy->datetime_at_utc . ' +1 day')))
                        ->last() ?? null;
                    }
                }
    
            }
    
            foreach($targets as $target){
                if(!empty($target->user)){
                    $full_name = $target->user->full_name ?? '-';
                    $type = "Staff";
                } else {
                    $full_name = $target->resident->full_name ?? '-';
                    $type = "Resident";
                }
    
                if($date_carbon >= $today){
                    if($now < $start_time){
                        $attendance_badge = '<span class="badge badge-pill badge-secondary">N/A</span>';
                    } else {
                        if($policy->attendance == 0){
                            $color = (isset($target->found)) ? 'danger':'success';
                            $attend = (isset($target->found)) ? 'Absent':'Present';
                        } else {
                            $color = (isset($target->found)) ? 'success':'danger';
                            $attend = (isset($target->found)) ? 'Present':'Absent';
                        }
                        $attendance_badge = '<span class="badge badge-pill badge-'.$color.'">'.$attend.'</span>';
                    }
                } else {
                    if($policy->attendance == 0){
                        $color = (isset($target->found)) ? 'danger':'success';
                        $attend = (isset($target->found)) ? 'Absent':'Present';
                    } else {
                        $color = (isset($target->found)) ? 'success':'danger';
                        $attend = (isset($target->found)) ? 'Present':'Absent';
                    }
                    $attendance_badge = '<span class="badge badge-pill badge-'.$color.'">'.$attend.'</span>';
                }
    
                $data = collect([
                    'name' => $full_name,
                    'type' => $type,
                    'attendance' => $attendance_badge,
                    'curr_loc' => $target->current_location ?? '-',
                    'detected_at' => $target->found->occured_at_tz ?? '-',
                ]);
    
                $data_update->push($data);
            }

        }


        return response()->json([
            "success" => "Attendance updated successfully.",
            "data" => $data_update
        ], 200);
    }

     /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeTest(Request $request)
    {
        //
        $requestall = $request->all();
        $this->console_log($requestall);
        $image_id = "image-input";
        $alias = $request->get('alias');
        $floor_number = $request->get('number');
        $this->console_log($request->get('image-input'));
  

        // $floor_id = Floor::Create([
        //     'building_id' => 1,
        //     'number' => $floor_number,
        //     'alias' => $alias,
        // ])->id;

        // if ($request->hasFile($image_id)) {
        //     if ($request->file($image_id)->isValid()) {
        //         $validated = $request->validate([
        //             'image' => 'mimes:jpeg,png|max:16384',
        //         ]);
        //         $extension = $request[$image_id]->extension();
        //         $current = Carbon::now()->format('Y-m-d-H-i-s');
        //         $request[$image_id]->storeAs('/public', $current.".".$extension);
        //         $url = Storage::url($current.".".$extension);
        //         $file = MapFile::updateOrCreate(
        //             ['floor_id' => $floor_id],
        //             ['name' => $current,
        //             'url' => $url,
        //         ]);
        //         Session::flash('success', "Success!");
        //         return Redirect::back();
        //     }
        // }
        // abort(500, 'Could not upload floor plan');
    }

    public function store (Request $request) {

        $requestall = $request->all();
        $image_id = "";
        $alias = "";
        foreach ($requestall as $key => $value) {
            $this->console_log($key);
            if (str_contains($key, 'image')) { 
                $image_id = $key;
            }
            if (str_contains($key, 'selectFloor')) { 
                $floor_id = $value;
            }
            if (str_contains($key, 'alias')) { 
                $alias = $value;
            }
        }
    
        Floor::where('id', $floor_id)
                    ->update(['alias' => $alias]);

        if ($request->hasFile($image_id)) {
            //  Let's do everything here
            if ($request->file($image_id)->isValid()) {
                //
                $validated = $request->validate([
                    'image' => 'mimes:jpeg,png|max:16384',
                ]);
                $extension = $request[$image_id]->extension();
                $current = Carbon::now()->format('Y-m-d-H-i-s');
                $request[$image_id]->storeAs('/public', $current.".".$extension);
                $url = Storage::url($current.".".$extension);
                $file = MapFile::updateOrCreate(
                    ['floor_id' => $floor_id],
                    ['name' => $current,
                    'url' => $url,
                ]);
                Session::flash('success', "Success!");
                return Redirect::back();
            }
        }
        abort(500, 'Could not upload image :(');
    }

    public function viewUploads () {
        $images = MapFile::all();
        return view('map.view_uploads')->with('images', $images);
    }

    function console_log( $data ){
        echo '<script>';
        echo 'console.log('. json_encode( $data ) .')';
        echo '</script>';
      }
}

