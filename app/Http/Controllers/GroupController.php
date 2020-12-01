<?php

namespace App\Http\Controllers;

use App\Company;
use App\Building;
use App\Group;
use App\Project;
use App\Schedule;
use App\Tag;
use App\Timeblocks;
use App\User;
use App\Services\CalendarService;
use DB;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;

class GroupController extends Controller
{
    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    function __construct()
    {
        $this->middleware('permission:group-list|group-create|group-edit|group-delete', ['only' => ['index','show']]);
        $this->middleware('permission:group-create', ['only' => ['create','store']]);
        $this->middleware('permission:group-edit', ['only' => ['edit','update']]);
        $this->middleware('permission:group-delete', ['only' => ['destroy']]);
    }
    
    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function index()
    {
        $groups = Group::orderBy('id','asc')->get();
        $projects = Project::get();
		
        return view('groups.index',compact('groups', 'projects'));
    }
    
    /**
    * Show the form for creating a new resource.
    * To check if a user is in a group.
	*
    * @return \Illuminate\Http\Response
    */
    public function create()
    {
        $projects = Project::get();
        $schedules = Schedule::pluck('name', 'id')->all();
		$tagsNull = Tag::doesntHave('group')->get();
		$usersNull = User::doesntHave('group')->get();
        
		return view('groups.create', compact('projects', 'schedules', 'tagsNull', 'usersNull', 'usersNull'));
    }
    
    /**
    * Store a newly created resource in storage.
    *	-Store the relationship between project and group  
	*	-Store the relationship between group and user 
	*
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    public function store(Request $request)
    {
        if($request->has('validate')){
            $validator = Validator::make($request->all(), [
                'name' => 'required|unique:groups,name'
            ]);
    
            if($validator->fails()){
                return response()->json([
                    'errors' => $validator->errors()]);
            }
            return response()->json([
                'success'=>'No errors.'], 
            200);
        }
        else{
            $group = Group::create($request->all());
            $group->projects()->attach($request['projects_id']);

            $schedule = Schedule::create();
            $group->schedule()->save($schedule);

            $data = collect($group);
            $data->put('projects', $group->projects);

            $projectsNull = Project::doesntHave('groups')->get();  
    
            return response()->json([
                'group' => $data,
                'projectsNull' => $projectsNull,
                'success' => '<strong>'.$group->name.'</strong> created successfully!'
            ], 200);
        }
	}
    
    /**
    * Display the specified resource.
    *
    * @param  \App\Group $group
    * @return \Illuminate\Http\Response
    */
    public function show(Group $group, CalendarService $calendarService)
    {
        $projectsNull = Project::doesntHave('groups')->get();

        /* For User-Tag Assignment */
        $usersNull = User::doesntHave('group')->get();
        $tagsNull = Tag::doesntHave('user')->get();

        /* For Schedule */
        $weekDays     = Timeblocks::WEEK_DAYS;
        $calendarData = $calendarService->generateCalendarData($weekDays, $group->schedule->id);
        $companies = Company::pluck('name', 'id')->all();

        return view('groups.show', compact('group', 'projectsNull', 'usersNull', 'tagsNull', 'weekDays', 'calendarData', 'companies'));
    }
    
    /**
    * Show the form for editing the specified resource.
    *
    * @param  \App\Group $group
    * @return \Illuminate\Http\Response
    */
    public function edit(Group $group)
    {	
        $schedules = Schedule::pluck('name', 'id')->all();
        $projects = Project::get();
        $tagsNull = Tag::doesntHave('group')
            ->get()
            ->merge($group->tags) //merge with the tags of this group
            ->sortBy('id') // sort the collection according to id
            ->values(); //reset the key
        $usersNull = User::doesntHave('group')
            ->get()
            ->merge($group->users) //merge with the tags of this group
            ->sortBy('id') // sort the collection according to id
            ->values(); //reset the key
        
		return view('groups.edit', compact('group', 'schedules', 'projects', 'tagsNull', 'usersNull'));
    }
    
    /**
    * Update the specified resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  \App\Group $group
    * @return \Illuminate\Http\Response
    */
    public function update(Request $request, Group $group)
    {
        request()->validate([
            'name' => 'required|unique:groups,name,'.$group->id
        ]);
        
        $group->update($request->all());

        if($request->has('projects_id')){
            $group->projects()->sync($request['projects_id']);
        }

        if(!empty($request['schedule_id'])){
            if(!empty($group->schedule)){
                $group->schedule()->dissociate()->save();
            }
            $schedule = Schedule::find($request['schedule_id']);
            $schedule->groups()->save($group);
        }

        if($request->has('tags_id')){
            if($group->tags()->exists()){
                foreach($group->tags as $pastTag){
                    $pastTag->group()->dissociate()->save();
                }
            }
            foreach($request['tags_id'] as $tagId){
                $tag = Tag::find($tagId);
                $group->tags()->save($tag);
            }
        }
        
        if($request->has('users_id')){
            if($group->users()->exists()){
                foreach($group->users as $pastUser){
                    $pastUser->group()->dissociate()->save();
                }
            }
            foreach($request['users_id'] as $userId){
                $user = User::find($userId);
                $group->users()->save($user);
            }
        }
		
        return redirect()->route('groups.index')
            ->with('success','Group updated successfully');
    }
   
    /**
    * Remove the specified resource from storage.
    *
    * @param  \App\Group $group
    * @return \Illuminate\Http\Response
    */
    public function destroy(Group $group)
    {
        /* Dissociate user and tag */
        foreach($group->users as $user){
            $user->group()->dissociate()->save();
            $tag = $user->tag;
            $tag->user()->dissociate()->save();
        }
        
        $name = $group->name;
        $group->delete();
		
        return response()->json([
            'success'=> '<strong>'.$name.'</strong> deleted successfully!'],
            200);
    }
}