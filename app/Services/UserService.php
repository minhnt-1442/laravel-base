<?php

namespace App\Services;

use App\User;
use App\Models\Role;

class UserService
{
    /**
     * Get list user.
     *
     * @return object
     */
    public function getListUser()
    {
        $users = User::all();

        return $users;
    }

    /**
     * Create a user.
     *
     * @param array $input
     *
     * @return object
     */
    public function createUser($input)
    {
        $user = User::create($input)->assignRole(Role::MEMBER);

        return $user;
    }

    /**
     * Update a user.
     *
     * @param array $input
     * @param User $user
     *
     * @return bool
     */
    public function updateUser($input, $user)
    {
        $response = $user->update($input);

        return $response;
    }

    /**
     * Delete a user.
     *
     * @param User $user
     *
     * @return bool
     */
    public function deleteUser($user)
    {
        $response = $user->delete($user->id);

        return $response;
    }
}