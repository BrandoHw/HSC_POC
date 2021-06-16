<?php

namespace App\Http\Controllers;

use App\Floor;
use App\GatewayZone;
use App\Location;
use App\MapFile;
use App\Reader;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class FloorController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:floor-list|floor-create|floor-edit|floor-delete', ['only' => ['index','show']]);
        $this->middleware('permission:floor-create', ['only' => ['create','store']]);
        $this->middleware('permission:floor-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:floor-delete', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $floors = Floor::with('map')->orderBy('number', 'asc')->get();
        return view('floors.index',compact('floors'));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
     
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $requestall = $request->all();
        $image_id = "image-input";
        $alias = $request->get('alias');
        $floor_number = $request->get('number');
        $floor_numbers = Floor::get()->pluck('number')->toArray();
        $validator = Validator::make($request->all(), [
            'number' => [
                'required',
                'integer',
                'gte:0',
                function ($attribute, $value, $fail) use($floor_number, $floor_numbers){
                    if (in_array($floor_number, $floor_numbers)) {
                        $fail('The '.$attribute.' is invalid.');
                    }
                },
            ],
        ]);
      
        if ($validator->fails()) {
            //
            Session::flash('failure', $validator->errors()->first());
            return Redirect::back();
        }
        $imageValidator = Validator::make($request->all(), [
            'image-input' => 'mimes:jpeg,png|max:16384',
        ]);
        
        if ($imageValidator->fails()) {
            //
            Session::flash('image-failure', $imageValidator->errors()->first());
            return Redirect::back();
        }

        $floor_id = Floor::Create([
            'building_id' => 1,
            'number' => $floor_number,
            'alias' => $alias,
        ])->floor_id;

        Floor::where('floor_id', $floor_id)->update(['id' => $floor_id]);

        if ($request->hasFile($image_id)) {
            if ($request->file($image_id)->isValid()) {
                $filename = Storage::disk('s3')->putFile('floor', $request[$image_id]);
                $url = Storage::disk('s3')->url($filename);

                $file = MapFile::updateOrCreate(
                    ['floor_id' => $floor_id],
                    ['name' => $filename, //Previously used $current
                    'url' => $url,
                ]);
                Session::flash('success', "Success!");
                return Redirect::back();
            }else{
                Session::flash('failure', "Failure");
                return Redirect::back();
            }
        }
        Session::flash('success', "Success");
        return Redirect::back();
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $requestall = $request->all();
        $image_id = "";
        $alias = "";
        $floor_number = "";
        $floor_number_key = "";
        foreach ($requestall as $key => $value) {
            if (str_contains($key, 'image-input')) { 
                $image_id = $key;
            }
            if (str_contains($key, 'number')) { 
                $floor_number = $value;
                $floor_number_key = $key;
            }
            if (str_contains($key, 'alias')) { 
                $alias = $value;
            }
        }
        $floor_numbers = Floor::get()->pluck('number')->toArray();
        $floor_number_current = Floor::where('id', $id)->get()[0]->number;

        if (($key = array_search($floor_number_current, $floor_numbers)) !== false) {
            unset($floor_numbers[$key]);
        }
        $validator = Validator::make($request->all(), [
            $floor_number_key => [
                'required',
                'integer',
                'gte:0',
                function ($attribute, $value, $fail) use($floor_number, $floor_numbers){
                    if (in_array($floor_number, $floor_numbers)) {
                        $fail('The '.$attribute.' is invalid.');
                    }
                },
            ],
        ]);
        if ($validator->fails()) {
            Session::flash('failure', "Invalid");
            return Redirect::back();
        }
      
     
        $floor = Floor::find($id);
        $floor->number = $floor_number;
        $floor->alias = $alias;
        $floor->save();

        $floor_id = $id;
        if ($request->hasFile($image_id)) {
            if ($request->file($image_id)->isValid()) {
                $imageValidator = Validator::make($request->all(), [
                    $image_id => 'mimes:jpeg,png|max:16384',
                ]);
                if ($imageValidator->fails()) {
                    Session::flash('image-failure', "Invalid");
                    return Redirect::back();
                }
                $oldUrl = MapFile::where('floor_id', $floor_id)->get()[0]->name;
                $filename = Storage::disk('s3')->putFile('floor', $request[$image_id]);
                Storage::disk('s3')->delete($oldUrl);
                $url = Storage::disk('s3')->url($filename);
                $file = MapFile::updateOrCreate(
                    ['floor_id' => $floor_id],
                    ['name' => $filename, //Previously used $current
                    'url' => $url,
                ]);
                Session::flash('success-update', "Success!");
                return Redirect::back();
            }
        }
        Session::flash('success-update', "Success!");
        return Redirect::back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        Floor::destroy($id);
        Session::flash('success', "Success!");
        return Redirect::back();


    }

   /**
     * Remove the specified resource from storage via post request.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroyHref($id)
    {
        //
        $floor_id = Floor::find($id)->floor_id;
        $oldUrl = MapFile::where('floor_id', $floor_id)->get()[0]->name;
        Storage::disk('s3')->delete($oldUrl);

        //Find and delete all locations, unassign all gateways, delete all zones
        $floor = Floor::with('locations')->find($id);
        $ids = [];

        foreach ($floor->locations as $location){
            array_push($ids, $location->location_master_id);
        }
        $gateways = Reader::whereIn('location_id', $ids)->get();
    
        $gatewayMacs = $gateways->pluck('mac_addr');
        $gatewayZones = GatewayZone::whereIn('mac_addr', $gatewayMacs)->get();
        foreach($gatewayZones as $gatewayZone){
            $gatewayZone->delete();
        }
        foreach($gateways as $gateway){
            $gateway->update(['location_id' => null]);
        }
        $deletedRows = Location::whereIn('location_master_id', $ids)->delete();

        Floor::destroy($id);
        Session::flash('success-destroy', "Success!");
        return Redirect::back();

    }
}
