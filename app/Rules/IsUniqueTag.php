<?php

namespace App\Rules;

use App\Tag;
use Illuminate\Contracts\Validation\Rule;

class IsUniqueTag implements Rule
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

        $tagsNull = Tag::doesntHave('resident')
            ->doesntHave('user')
            ->pluck('beacon_id')
            ->all();

        if(isset($current)){
            array_push($tagsNull, $current->beacon_id);
        }

        return in_array($value, $tagsNull);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'This tag is taken. Please select another tag.';
    }
}
