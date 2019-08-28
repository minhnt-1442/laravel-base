<?php

namespace App\Http\Requests;

class LoginRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
          'email' => 'required|string|email',
          'password' => 'required|string',
          'remember_me' => 'boolean',
        ];
    }
}
