<?php

namespace App\Http\Requests\API;

use App\Models\User;
use InfyOm\Generator\Request\APIRequest;

class UpdateUserAPIRequest extends APIRequest
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
        if (request()->has('password')) {
            return [
                'password' => 'required|confirmed|min:8',
            ];
        }

        $id = $this->id ?? $this->user;
        $rules = User::$rules;
        $rules['email'] = "required|email|unique:users,email," . $id . "|max:100";
        return $rules;
    }
}
