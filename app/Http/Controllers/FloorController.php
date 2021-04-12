<?php

namespace App\Http\Controllers;

use App\Floor;
use App\MapFile;
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
        $this->middleware('permission:floor-list|floor-create|floor-edit|floor-delete', ['only' => ['index','show', 'edit']]);
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
        $this->console_log($floors);
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
        $this->console_log($requestall);
        $image_id = "image-input";
        $alias = $request->get('alias');
        $floor_number = $request->get('number');
        $this->console_log($request->get('image-input'));
        $floor_numbers = Floor::get()->pluck('number')->toArray();
        $validator = Validator::make($request->all(), [
            'number' => [
                'required',
                'integer',
                'gte:0',
                function ($attribute, $value, $fail) use($floor_number, $floor_numbers){
                    $this->console_log($floor_numbers);
                    if (in_array($floor_number, $floor_numbers)) {
                        $this->console_log($attribute);
                        $this->console_log($value);
                        $fail('The '.$attribute.' is invalid.');
                    }
                },
            ],
        ]);
        if ($validator->fails()) {
            //
            Session::flash('failure', "Invalid");
            return Redirect::back();
        }

        $floor_id = Floor::Create([
            'building_id' => 1,
            'number' => $floor_number,
            'alias' => $alias,
        ])->id;

        if ($request->hasFile($image_id)) {
            if ($request->file($image_id)->isValid()) {
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
        abort(500, 'Could not upload floor plan');
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
                    $this->console_log($floor_numbers);
                    if (in_array($floor_number, $floor_numbers)) {
                        $this->console_log("Attribute");
                        $this->console_log($attribute);
                        $this->console_log("Value");
                        $this->console_log($value);
                     
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
        abort(500, 'Could not upload floor plan');
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
        Floor::destroy($id);
        Session::flash('success-destroy', "Success!");
        return Redirect::back();

    }
    function console_log( $data ){
        echo '<script>';
        echo 'console.log('. json_encode( $data ) .')';
        echo '</script>';
      }
}
