<?php

namespace App\Http\Requests\API;

use InfyOm\Generator\Request\APIRequest;

class RegisterAPIRequest extends APIRequest
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
            'name' => 'required|min:4|max:50',
            'email' => 'required|email|unique:users|max:100',
            'password' => 'required|confirmed|min:8',
        ];
    }
}
