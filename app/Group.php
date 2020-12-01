<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'detail'
    ];
	
	/**
     * Get the projects records associated with the group.
     */
	public function projects()
	{
		return $this->belongsToMany(Project::class);
	}

	/**
     * Get the schedule records associated with the group.
     */
	public function schedule()
	{
		return $this->hasOne(Schedule::class);
	}

	/**
     * Get the users records associated with the group.
     */
	public function users()
	{
		return $this->hasMany(User::class);
	}

	/**
     * Get the tags records associated with the group.
     */
	public function tags()
	{
		return $this->hasManyThrough(Tag::class, User::class);
	}
}