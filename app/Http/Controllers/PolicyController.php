<?php

namespace App\Http\Controllers;

use App\Location;
use App\Policy;
use App\PolicyType;
use App\Resident;
use App\Scope;
use App\Tag;
use App\User;
use App\Services\TagTargetService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;


class PolicyController extends Controller
{
    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    function __construct()
    {
        $this->middleware('permission:policy-list|policy-create|policy-edit|policy-delete', ['only' => ['index','edit']]);
        $this->middleware('permission:policy-create', ['only' => ['create','store']]);
        $this->middleware('permission:policy-edit', ['only' => ['update']]);
        $this->middleware('permission:policy-delete', ['only' => ['destroys']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $policies = Policy::orderBy('rules_id', 'asc')->get();
        return view('policies.index', compact('policies'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(TagTargetService $tagTargetService)
    {
        $residents = Resident::whereHas('tag')->with('tag')->orderBy('resident_fName', 'asc')->get();
        $residents_all_count = Resident::count();
        $residents_count = $residents->count();

        $users = User::whereHas('tag')->with('tag')->orderBy('fName', 'asc')->get();
        $users_all_count = User::count();
        $users_count = $users->count();

        $targets = $tagTargetService->generateTagTarget($residents ,$users, NULL);

        $locations = Location::orderBy('location_master_id', 'asc')->get();
        return view('policies.create', compact('targets', 'residents_all_count', 'residents_count',
            'users_all_count', 'users_count', 'locations'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|unique:rules_table,description,NULL,rules_id,deleted_at,NULL',
            'alert' => 'required|string',
            'type' => 'required|string',
            'target' => 'required|string',
            'day' => 'required|integer',
            'start-time' => 'required|string',
            'duration' => 'required|string',
            'location' => 'required|array'
        ]);

        if($validator->fails()){
            return response()->json([
                "errors" => $validator->errors()]);
        }

        $params = null;
        switch($request['type']){
            case "1":
                $params = ['attendance-option' => 'required|string'];
                break;
            case "2":
                $params = ['battery' => 'required|string'];
                break;
            case "5":
                $params = ['geofence-option' => 'required|string'];
                break;
            case "4":
            case "6":
                $params = [
                    'frequency' => 'required|string',
                    'x-value' => 'required|string',
                    'y-value' => 'required|string',
                    'z-value' => 'required|string'
                ];
                break;
        };
        if(isset($params)){
            $type_validator = Validator::make($request->all(), $params);
            if($type_validator->fails()){
                return response()->json([
                    "errors" => $type_validator->errors()]);
            }
        }

        if($request['target'] == 'custom'){
            $target_validator = Validator::make($request->all(), ['custom-target' => 'required|array']);
            if($target_validator->fails()){
                return response()->json([
                    "errors" => $target_validator->errors()]);
            }
        }

        $data = [
            'description' => $request['name'],
            'alert_action' => (int)$request['alert'],
        ];

        $add_on = null;
        switch($request['type']){
            case '1':
                $add_on = ["attendance" => (int)$request['attendance-option']];
                break;
            case '2':
                $add_on = ["battery_threshold" => (int)$request['battery']];
                break;
            case '5':
                $add_on = ["geofence" => (int)$request['geofence-option']];
                break;
            case '4':
            case '6':
                $add_on = [];
                if((float)$request['x-value'] >= 0){
                    $add_on = Arr::add($add_on, 'x_threshold', (float)$request['x-value']);
                    $add_on = Arr::add($add_on, 'x_frequency', (int)$request['frequency']);
                }
                if((float)$request['y-value'] >= 0){
                    $add_on = Arr::add($add_on, 'y_threshold', (float)$request['y-value']);
                    $add_on = Arr::add($add_on, 'y_frequency', (int)$request['frequency']);
                }
                if((float)$request['z-value'] >= 0){
                    $add_on = Arr::add($add_on, 'z_threshold', (float)$request['z-value']);
                    $add_on = Arr::add($add_on, 'z_frequency', (int)$request['frequency']);
                }
                break;
        }

        if(isset($add_on)){
            $data = Arr::collapse([$data, $add_on]);
        }

        /* Store new policy */
        $policy = Policy::create($data);

        /* Link policy type */
        $policy_type = PolicyType::find((int)$request['type']);
        $policy_type->policies()->save($policy);

        /* Link scope */
        $scope = Scope::create([
            'days' => (int)$request['day'],
            'start_time' => Carbon::createFromFormat('g:i A', $request['start-time'])->format('H:i:s'),
            'duration' => (int)$request['duration'],
        ]);
        $scope->policy()->save($policy);

        /* Get target data */
        $residents = [];
        $users = [];
        switch($request['target']){
            case "all":
                $residents = Resident::whereHas('tag')->get();
                $users = User::whereHas('tag')->get();
                break;
            case "user-only":
                $users = User::whereHas('tag')->get();
                break;
            case "resident-only":
                $residents = Resident::whereHas('tag')->get();
                break;
            case "custom":
                foreach($request['custom-target'] as $target){
                    $input = explode('-', $target);
                    if($input[0] == "R"){
                        array_push($residents, Resident::find((int)$input[1]));
                    } else {
                        array_push($users, User::find((int)$input[1]));
                    }
                }
                break;
        }

        $tags = [];
        if(!empty($residents)){
            foreach($residents as $resident){
                array_push($tags, $resident->tag->beacon_id);
            }
        }
        if(!empty($users)){
            foreach($users as $user){
                array_push($tags, $user->tag->beacon_id);
            }
        }

        /* Sync scope_beacons_table */
        if(!empty($tags)){
            $scope->tags()->sync($tags);
        }

        /* Sync scope_beacons_table */
        if(!empty($request['location'])){
            $scope->locations()->sync($request['location']);
        }

        return response()->json([
            "success" => "Policy added successfully."
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  App\Policy  $policy
     * @return \Illuminate\Http\Response
     */
    public function edit(Policy $policy)
    {
        $residents = Resident::whereHas('tag')->orderBy('resident_fName', 'asc')->get();
        $users = User::whereHas('tag')->orderBy('fName', 'asc')->get();
        $locations = Location::orderBy('location_master_id', 'asc')->get();
        return view('policies.edit', compact('policy', 'residents', 'users', 'locations'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  App\Policy  $policy
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Policy $policy)
    {
        {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|unique:rules_table,description,'.$policy->rules_id.',rules_id,deleted_at,NULL',
                'alert' => 'required|string',
                'type' => 'required|string',
                'target' => 'required|string',
                'day' => 'required|integer',
                'start-time' => 'required|string',
                'duration' => 'required|string',
                'location' => 'required|array'
            ]);
    
            if($validator->fails()){
                return response()->json([
                    "errors" => $validator->errors()]);
            }
    
            $params = null;
            switch($request['type']){
                case "1":
                    $params = ['attendance-option' => 'required|string'];
                    break;
                case "2":
                    $params = ['battery' => 'required|string'];
                    break;
                case "5":
                    $params = ['geofence-option' => 'required|string'];
                    break;
                case "4":
                case "6":
                    $params = [
                        'frequency' => 'required|string',
                        'x-value' => 'required|string',
                        'y-value' => 'required|string',
                        'z-value' => 'required|string'
                    ];
                    break;
            };
            if(isset($params)){
                $type_validator = Validator::make($request->all(), $params);
                if($type_validator->fails()){
                    return response()->json([
                        "errors" => $type_validator->errors()]);
                }
            }
    
            if($request['target'] == 'custom'){
                $target_validator = Validator::make($request->all(), ['custom-target' => 'required|array']);
                if($target_validator->fails()){
                    return response()->json([
                        "errors" => $target_validator->errors()]);
                }
            }
    
            $data = [
                'description' => $request['name'],
                'alert_action' => (int)$request['alert'],
                'battery_threshold' => null,
                'attendance' => null,
                'geofence' => null,
                'x_threshold' => null,
                'x_frequency' => null,
                'y_threshold' => null,
                'y_frequency' => null,
                'z_threshold' => null,
                'z_frequency' => null,
            ];
    
            switch($request['type']){
                case '1':
                    $data['attendance'] = (int)$request['attendance-option'];
                    break;
                case '2':
                    $data['battery_threshold'] = (int)$request['battery'];
                    break;
                case '5':
                    $data['geofence'] = (int)$request['geofence-option'];
                    break;
                case '4':
                case '6':
                    if((float)$request['x-value'] >= 0){
                        $data['x_threshold'] = (float)$request['x-value'];
                        $data['x_frequency'] = (int)$request['frequency'];
                    }
                    if((float)$request['y-value'] >= 0){
                        $data['y_threshold'] = (float)$request['y-value'];
                        $data['x_frequency'] = (int)$request['frequency'];
                    }
                    if((float)$request['z-value'] >= 0){
                        $data['_threshold'] = (float)$request['z-value'];
                        $data['x_frequency'] = (int)$request['frequency'];
                    }
                    break;
            }
    
            /* Update policy */
            $policy->update($data);
    
            /* Remove previous policy type */
            $policy->policyType()->dissociate()->save();

            /* Link new policy type */
            $policy_type = PolicyType::find((int)$request['type']);
            $policy->policyType()->associate($policy_type)->save();
            
            /* Update scope */
            $scope = $policy->scope;
            $scope->days = (int)$request['day'];
            $scope->start_time = Carbon::createFromFormat('g:i A', $request['start-time'])->format('H:i:s');
            $scope->duration = (int)$request['duration'];
            $scope->save();
            
            /* Get target data */
            $residents = [];
            $users = [];
            switch($request['target']){
                case "all":
                    $residents = Resident::whereHas('tag')->get();
                    $users = User::whereHas('tag')->get();
                    break;
                case "user-only":
                    $users = User::whereHas('tag')->get();
                    break;
                case "resident-only":
                    $residents = Resident::whereHas('tag')->get();
                    break;
                case "custom":
                    foreach($request['custom-target'] as $target){
                        $input = explode('-', $target);
                        if($input[0] == "R"){
                            array_push($residents, Resident::find((int)$input[1]));
                        } else {
                            array_push($users, User::find((int)$input[1]));
                        }
                    }
                    break;
            }
    
            $tags = [];
            if(!empty($residents)){
                foreach($residents as $resident){
                    array_push($tags, $resident->tag->beacon_id);
                }
            }
            if(!empty($users)){
                foreach($users as $user){
                    array_push($tags, $user->tag->beacon_id);
                }
            }
    
            /* Sync scope_beacons_table */
            if(!empty($tags)){
                $scope->tags()->sync($tags);
            }
    
            /* Sync scope_beacons_table */
            if(!empty($request['location'])){
                $scope->locations()->sync($request['location']);
            }
    
            return response()->json([
                "success" => "Policy updated successfully."
            ], 200);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  array  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * Remove the specified resources from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function destroys(Request $request)
    {
        $ids = $request->policies_id;

        Policy::destroy($ids);

        if(count($ids) > 1){
            return response()->json([
                "success" => "Policies deleted successfully."
            ], 200);
        } else {
            return response()->json([
                "success" => "Policy deleted successfully."
            ], 200);
        }
    }
}
