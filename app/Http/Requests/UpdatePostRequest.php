<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class UpdatePostRequest extends FormRequest
{
    public $validator = null;

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
            'title' => 'required',
            'content' => 'required',
            'topic_id' => 'required'
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $this->validator = $validator;
    }
}
