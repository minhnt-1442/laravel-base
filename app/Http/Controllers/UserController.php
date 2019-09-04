<?php

namespace App\Http\Controllers;

use App\User;
use App\Services\UserService;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\SignUpRequest;
use App\Http\Requests\UpdateUserRequest;

class UserController extends Controller
{
    /**
     * @var UserService
     */
    private $userService;

    /**
     * @param userService
     */
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('index', User::class);
        $users = $this->userService->getListUser();

        return response()->json($users);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  SignUpRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SignUpRequest $request)
    {
        $this->authorize('create', User::class);
        $user = $this->userService->createUser($request->all());

        return response()->json([
            'message' => trans('auth.success'),
            'data' => $user
        ]);
     }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::find($id);
        $member = Auth::user();
        $this->authorize('show', $user, $member);

        return response()->json($user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UpdateUserRequest  $request
     * @param  User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        $this->authorize('update', $user);
        $response = $this->userService->updateUser($request->all(), $user);

        return response()->json([
            'success' => $response
        ]);
     }

    /**
     * Remove the specified resource from storage.
     *
     * @param  User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $this->authorize('delete', $user);
        $response = $this->userService->deleteUser($user);

        return response()->json(['success' => $response ? true : false]);
    }
}
