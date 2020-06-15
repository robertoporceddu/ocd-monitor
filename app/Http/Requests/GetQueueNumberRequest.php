<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GetQueueNumberRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
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
            'callerid' => [ 'required', 'regex:(^[0,3]\d{6,10}$)' ],
            'fromid' => [ 'required', 'regex:(^[0,3]\d{6,10}$)' ]
        ];
    }
}
