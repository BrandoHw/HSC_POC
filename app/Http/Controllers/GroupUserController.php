<?php

namespace App\Http\Controllers;

use App\Group;
use App\Tag;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class GroupUserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     * 
     * @param \App\Group $group
     * @return \Illuminate\Http\Response
     */
    public function create(Group $group)
    {
        $usersNull = User::doesntHave('group')->get();
        $refinedUsersNull = collect();
        foreach($usersNull as $user){
            $data = collect($user);
            $data->put('role', '<span class="badge badge-pill badge-dark" style="background-color: '
                .$user->roles[0]->color
                .'">'
                .$user->getRoleNames()[0]
                .'</span>');
            $data->put('rowId', 'trNullUser-'.$user->id);
            $refinedUsersNull->push($data);
        }

        $tagsNull = Tag::doesntHave('user')->get();

        return response()->json([
            'usersNull' =>$refinedUsersNull,
            'tagsNull' => $tagsNull,
        ],
        200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param \App\Group $group
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Group $group)
    {
        foreach($request['users'] as $userInfo){
            $user = User::find($userInfo['id']);
            $tag = Tag::find($userInfo['tagId']);
            $user->tag()->save($tag);

            $group->users()->save($user);
        }
        
        return response()->json([
            'success' => 'Users added successfully!'
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
     * @param  App\Group $group
     * @param  App\User $user
     * @return \Illuminate\Http\Response
     */
    public function edit(Group $Group, User $user)
    {   
        /* Get tagsNull with user's current tag */
        $tag = $user->tag;
        $tag->user()->dissociate()->save();
        $tagsNull = Tag::doesntHave('user')->get();

        $user->tag()->save($tag);

        return response()->json([
            'user' => $user,
            'tagsNull' => $tagsNull],
            200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  App\Group $group
     * @param  App\User $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Group $Group, User $user)
    {
        $tag = Tag::find($request['tagId']);
        $user->tag()->save($tag);

        $refinedUser = collect($user);
        $refinedUser->put('tag', $user->tag);

        return response()->json([
            'user' => $refinedUser,
            'success' => 'Tag of <strong>'.$user->name.'</strong> updated successfully!'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  App\Group $group
     * @param  App\User $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(Group $Group, User $user)
    {
        $user->group()->dissociate()->save();
        $tag = $user->tag;
        $tag->user()->dissociate()->save();

        $usersNull = User::doesntHave('group')->get();

        $refinedUsersNull = collect();

        foreach($usersNull as $userNull){
            $data = collect($userNull);
            $data->put('roles', $userNull->roles);
            $refinedUsersNull->push($data);
        }

        return response()->json([
            'user' => $user,
            'usersNull' => $refinedUsersNull,
            'success' => '<strong>'.$user->name.'</strong> removed successfully!' 
        ], 200);
    }
}
