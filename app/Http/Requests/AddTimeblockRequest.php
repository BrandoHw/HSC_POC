<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;
use Gate;
use App\Rules\TimeblockAvailablility;

class AddTimeblockRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        //abort_if(Gate::denies('timeblock_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'schedule_id' => [
                'required'],
            'building_id' => [
                'required'],
            'company_id' => [
                'required'],
            'day' => [
                'required',
                'integer',
                'min:1',
                'max:7'],
            'start_time' => [
                'required',
                new TimeblockAvailablility(),
                'date_format:' . 'H:i'],
            'end_time'   => [
                'required',
                'after:start_time',
                'date_format:' . 'H:i'],
            ];  
    }
}
