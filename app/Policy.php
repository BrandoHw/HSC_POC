<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

class Policy extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'rules_table';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'rules_id';

      /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'description',
        'x_threshold', 'y_threshold', 'z_threshold',
        'x_frequency', 'y_frequency', 'z_frequency',
        'alert_action', 'attendance', 'geofence',
    ];

    /**
     * Get the policyType that owns the policy
     */
    public function policyType()
    {
        return $this->belongsTo(policyType::class, 'rules_type_id', 'rules_type_id');
    }

    /**
     * Get the scope that owns the policy
     */
    public function scope()
    {
        return $this->belongsTo(Scope::class, 'scope_id', 'scope_id');
    }

    /**
     * Get the policy's violence frequency.
     *
     * @return string
     */
    public function getFrequencyAttribute()
    {
        if(isset($this->x_frequency)){
            return $this->x_frequency;
        } elseif (isset($this->y_frequency)){
            return $this->y_frequency;
        } elseif (isset($this->z_frequency)){
            return $this->z_frequency;
        } else {
            return null;
        }
    }

    /**
     * Get the policy's target.
     *
     * @return string
     */
    public function getTargetTypeAttribute()
    {
        $residents_all = false;
        $users_all = false;

        $residents_count = $this->scope->tags->whereNotNull('resident')->count();
        $users_count = $this->scope->tags->whereNotNull('user')->count();

        if($residents_count == Resident::whereHas('tag')->count()){
            $residents_all = true;
        }

        if($users_count == User::whereHas('tag')->count()){
            $users_all = true;
        }

        if($residents_all && $users_all){
            return 'all';
        } elseif ($residents_all && !$users_all){
            if($users_count != 0){
                return 'custom';
            }
            return 'resident-only';
        }elseif (!$residents_all && $users_all){
            if($residents_count != 0){
                return 'custom';
            }
            return 'user-only';
        } else {
            return 'custom';
        }

    }

    /**
     * Get the policy's day.
     *
     * @return string
     */
    public function getDayTypeAttribute()
    {
        $num = $this->scope->days;

        switch($num){
            case 127:
                return "daily";
            case 62:
                return "weekdays";
            case 65:
                return "weekends";
            default:
                return "custom";
        }
    }

    /**
     * Get the policy's day.
     *
     * @return string
     */
    public function getDayAttribute()
    {
        $num = decbin($this->scope->days);
        $string =  str_split($num);

        if(strlen($num) < 7){
            array_unshift($string,"0");
        }
        return $string;
    }

    /**
     * Get the residents associated with this policy.
     *
     * @return string
     */
    public function getResidentsAttribute()
    {
        $residents = $this->scope->tags->whereNotNull('resident')
            ->pluck('resident')->all();
        
        $residents_id = [];

        foreach($residents as $resident){
            array_push($residents_id, "R-".$resident->resident_id);
        }

        return $residents_id;
    }

    /**
     * Get the users associated with this policy.
     *
     * @return string
     */
    public function getUsersAttribute()
    {
        $users = $this->scope->tags->whereNotNull('user')
            ->pluck('user')->all();
        
        $users_id = [];

        foreach($users as $user){
            array_push($users_id, "U-".$user->user_id);
        }

        return $users_id;

    }

    /**
     * Get the users associated with this policy.
     *
     * @return string
     */
    public function getAllTargetsAttribute()
    {
        $targets_id = Arr::collapse([$this->residents, $this->users]);
        return $targets_id;

    }

    /**
     * Get the users associated with this policy.
     *
     * @return string
     */
    public function getAllLocationsAttribute()
    {
        return $this->scope->locations->pluck('location_master_id')->all();

    }
}
