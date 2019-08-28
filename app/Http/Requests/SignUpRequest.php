<?php

namespace App\Http\Requests;

class SignUpRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
          'name' => 'required|string',
          'email' => 'required|string|email|unique:users',
          'password' => 'required|string|confirmed',
        ];
    }
}
