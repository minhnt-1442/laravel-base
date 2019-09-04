<?php

namespace App\Policies;

use App\User;
use App\User as Member;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function index(User $user)
    {
        return $user->can('get_list_user');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->can('create_user');
    }

    /**
     * Determine whether the user can show the model.
     *
     * @param  \App\User  $user
     * @param  \App\User  $member
     *
     * @return mixed
     */
    public function view(User $user, Member $member)
    {
        if ($user->hasRole('admin') || ($user->id === $member->id)) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function update(User $user)
    {
        return $user->can('update_user');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\User  $member
     * @return mixed
     */
    public function delete(User $user, Member $member)
    {
        return $user->can('delete_user');
    }
}
