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
use App\Location;
class GeneralController extends Controller
{
    //

    public function index (){
        // $rooms = Location::where('location_type_id', 2)->pluck('location_master_id')->all();
        // return in_array(11, $rooms);
        return view('test.test');
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

