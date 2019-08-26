<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\User;

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
        $user = User::create($request->only(['name', 'email', 'password']));
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
      $credentials = $request->only(['email', 'password']);
      if(!Auth::attempt($credentials))
          return response()->json([
              'message' => trans('auth.unauthorized')
          ], 401);
      $tokenResult = Auth::user()->createToken('Personal Access Token');
      $token = $tokenResult->token;
      if ($request->remember_me)
          $token->expires_at = Carbon::now()->addWeeks(1);
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

