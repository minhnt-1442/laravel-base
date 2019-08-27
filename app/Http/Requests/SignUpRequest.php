<?php

namespace App\Http\Requests;

use App\Http\Requests\BaseRequest;

class SignUpRequest extends BaseRequest
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
          'name' => 'required|string',
          'email' => 'required|string|email|unique:users',
          'password' => 'required|string|confirmed',
        ];
    }
}