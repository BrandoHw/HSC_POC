<?php

namespace App\Rules;

use App\Resident;
use App\User;
use Illuminate\Contracts\Validation\Rule;

class IsUniqueTarget implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($param)
    {
        $this->param = $param;
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
        $current = $this->param;

        $target_type = explode('-', $value)[0];
        $target_id = explode('-', $value)[1];

        if($target_type == "R"){
            $targetsNull = Resident::doesntHave('tag')->pluck('resident_id')->all();
        } else {
            $targetsNull = User::doesntHave('tag')->pluck('user_id')->all();
        }

        if(isset($current)){
            if(isset($current->user_id)){
                array_push($targetsNull, $current->user_id);
            } else {
                array_push($targetsNull, $current->resident_id);
            }
        }

        return in_array($target_id, $targetsNull);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'This target is taken. Please select another target.';
    }
}
