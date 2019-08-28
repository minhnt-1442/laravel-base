<?php

namespace App\Http\Controllers;

use App\Services\AuthService;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\User;
use App\Http\Requests\SignUpRequest;
use App\Http\Requests\LoginRequest;

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
     * @param  SignUpRequest $request
     * @return JsonResponse
     */
    public function signup(SignUpRequest $request)
    {
        $user = $this->authService->createUser($request);

        return response()->json([
            'message' => trans('auth.signup_success'),
            'user' => $user,
        ], 201);
    }

    /**
     * Login user and create token
     *
     * @param  LoginRequest $request
     * @return JsonResponse
     */
    public function login(LoginRequest $request)
    {
        $credentials = $request->only(['email', 'password']);
        if(!Auth::attempt($credentials)) {
            return response()->json([
                'message' => trans('auth.unauthorized')
            ], 401);
        }
        $response = $this->authService->handleLogin($request);

        return response()->json($response);
    }

    /**
     * Logout user (Revoke the token)
     *
     * @return JsonResponse
     */
    public function logout()
    {
        Auth::user()->token()->revoke();

        return response()->json([
            'message' => trans('auth.logout_success')
        ]);
    }

    /**
     * Get the authenticated User
     *
     * @return JsonResponse
     */
    public function user()
    {
        return response()->json(Auth::user());
    }
}
