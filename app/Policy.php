<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Arr;

class Policy extends Model
{
    use SoftDeletes;


    const frequency = [
        1 => "1 time",
        2 => "2 times",
        3 => "3 times",
        4 => "4 times",
        5 => "5 times",
        10 => "10 times",
        15 => "15 times",
        20 => "20 times",
    ];

    const target_type = [
        "A" => "Everyone",
        "U" => "User(s) Only", 
        "R" => "Resident(s) Only",
        "C" => "Custom",
    ];

    const day_type = [
        "daily" => "Daily",
        "weekend" => "Monday to Friday", 
        "weekdays" => "Saturday to Sunday",
        "custom" => "Custom",
    ];

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
        'description', 'battery_threshold',
        'x_threshold', 'y_threshold', 'z_threshold',
        'x_frequency', 'y_frequency', 'z_frequency',
        'alert_action', 'attendance', 'geofence',
    ];

    /**
     * Get the policyType that owns the policy
     */
    public function policyType()
    {
        return $this->belongsTo(PolicyType::class, 'rules_type_id', 'rules_type_id');
    }

    /**
     * Get the scope that owns the policy
     */
    public function scope()
    {
        return $this->belongsTo(Scope::class, 'scope_id', 'scope_id');
    }

    /**
     * Get the scope that owns the policy
     */
    public function alerts()
    {
        return $this->hasMany(Alert::class, 'rules_id', 'rules_id');
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
    public function getTargetTypeNameAttribute()
    {
        $target_type = Policy::target_type;
        $val = $this->scope->target_type;
        if(isset($val)){
            return $target_type[$val];
        } else {
            return "-";
        }
    }

    /**
     * Get the policy's day type.
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
     * Get the all targets associated with this policy.
     *
     * @return string
     */
    public function getAllTargetsAttribute()
    {
        $targets_id = Arr::collapse([$this->residents, $this->users]);
        return $targets_id;

    }

    /**
     * Get the all targets raw ids associated with this policy.
     *
     * @return string
     */
    public function getAllTargetsRawIdsAttribute()
    {
        
        $targets_id = $this->all_targets;
        $targets_id_raw = [];
        foreach($targets_id as $id){
            $id_raw = explode('-', $id)[1];
            array_push($targets_id_raw, $id_raw);
        }
        return $targets_id_raw;

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

    /**
     * Convert created_at to Asia/Kuala_Lumpur timezone.
     *
     * @return string
     */
    public function getCreatedAtTzAttribute()
    {
        $date = Carbon::createFromFormat('Y-m-d H:i:s', $this->created_at, 'UTC');
        $date->setTimezone('Asia/Kuala_Lumpur');
        return $date->format('Y-m-d g:i:s A');
    }

    /**
     * Convert updated_at to Asia/Kuala_Lumpur timezone.
     *
     * @return string
     */
    public function getUpdatedAtTzAttribute()
    {
        if(isset($this->updated_at)){
            $date = Carbon::createFromFormat('Y-m-d H:i:s', $this->updated_at, 'UTC');
            $date->setTimezone('Asia/Kuala_Lumpur');
            return $date->format('Y-m-d g:i:s A');
        } else {
            return "-";
        }
    }

    /**
     * Convert updated_at to Asia/Kuala_Lumpur timezone.
     *
     * @return string
     */
    public function getDatetimeAtUtcAttribute()
    {
        $datetime = Carbon::today('Asia/Kuala_Lumpur');
        $time = explode(":", $this->scope->start_time);
        $datetime->hour = $time[0];
        $datetime->minute = $time[1];
        $datetime->setTimezone('UTC');
        return $datetime->format('Y-m-d H:i:s');
    }

    /**
     * Convert updated_at to Asia/Kuala_Lumpur timezone.
     *
     * @return string
     */
    public function getTimeAtUtcAttribute()
    {
        $time = Carbon::parse($this->scope->start_time);
        return $time->format('g:i A');;
    }
}
