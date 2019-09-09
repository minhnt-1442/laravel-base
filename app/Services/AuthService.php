<?php

namespace App\Services;

use Mail;
use Auth;
use App\User;
use App\Models\Role;
use Carbon\Carbon;
use App\Mail\RegisterSuccessMailable;
use App\Jobs\RegistedUserMail;

class AuthService
{
    /**
     * Create user
     *
     * @param  $request
     * @return object
     */
    public function createUser($request)
    {
        $user = User::create($request->only(['name', 'email', 'password']))->assignRole(Role::MEMBER);

        RegistedUserMail::dispatch($user);

        return $user;
    }

    /**
     * handleLogin
     *
     * @param  $request
     * @return array [access_token]
     *               [token_type]
     *               [expires_at]
     */
    public function handleLogin($request)
    {
        $tokenResult = Auth::user()->createToken('Personal Access Token');
        $token = $tokenResult->token;
        if ($request->remember_me) {
            $token->expires_at = Carbon::now()->addWeeks(1);
        }
        $token->save();

        return [
            'access_token' => $tokenResult->accessToken,
            'token_type' => 'Bearer',
            'expires_at' => Carbon::parse(
                $tokenResult->token->expires_at
            )->toDateTimeString()
        ];
    }
}
