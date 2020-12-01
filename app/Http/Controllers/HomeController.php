<?php

namespace App\Http\Controllers;

use App\TagDataLog;
use App\Group;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $tag_data_logs = TagDataLog::latest('last_detected_at')->get();
        $groups = Group::pluck('name', 'id')->all();
        $groupNum = Group::count();
        return view('tag_data_logs.index', compact('tag_data_logs', 'groups', 'groupNum'));
    }
}
