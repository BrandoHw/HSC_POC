<?php

namespace App\Rules;
use App\Location;

use Illuminate\Contracts\Validation\Rule;

class RoomTypeOnly implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        //
        $rooms = Location::where('location_type_id', 2)->pluck('location_master_id')->all();
        return in_array($value, $rooms);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'This location_master_id is not a valid room, obtain list of rooms with GET /api/rooms';
    }
}
