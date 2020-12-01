<?php

namespace App\Rules;

use App\Timeblocks;
use Illuminate\Contracts\Validation\Rule;

class TimeblockAvailablility implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($timeblock = null)
    {
        //
        $this->timeblock = $timeblock;
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
       return Timeblocks::isTimeAvailable(request()->input('day'), $value, request()->input('end_time'), request()->input('schedule_id'), $this->timeblock);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'This time is not available';
    }
}
