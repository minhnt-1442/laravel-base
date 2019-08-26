<?php

namespace App\Http\Controllers;

use App\Services\AuthService;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\User;
use App\Http\Requests\SignUp;
use App\Http\Requests\Login;

class AuthController extends Controller
{

    /**
     * @var AuthService
     */
    protected $authService;
    
     /**
     * AuthController constructor.
     * @param AuthService $authService
     */
    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    /**
     * Create user
     *
     * @param  SignUp $request
     * @return [string] message
     */
    public function signup(SignUp $request)
    {
        $user = $this->authService->createUser($request);
        return response()->json([
            'message' => trans('auth.signupSuccess'),
            'user' => $user,
        ], 201);
    }

    /**
     * Login user and create token
     *
     * @param  Login $request
     * @return [string] access_token
     * @return [string] token_type
     * @return [string] expires_at
     */
    public function login(Login $request)
    {
        $response = $this->authService->handleLogin($request);
        return response()->json($response);
    }

    /**
     * Logout user (Revoke the token)
     *
     * @return [string] message
     */
    public function logout()
    {
        Auth::user()->token()->revoke();
        return response()->json([
            'message' => trans('auth.logoutSuccess')
        ]);
    }

    /**
     * Get the authenticated User
     *
     * @return [json] user object
     */
    public function user()
    {
        return response()->json(Auth::user());
    }
}
