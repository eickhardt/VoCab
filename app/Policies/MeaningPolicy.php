<?php

namespace App\Policies;

use App\User;
use App\Meaning;
use Illuminate\Auth\Access\HandlesAuthorization;

class MeaningPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any meanings.
     *
     * @param User $user
     * @return boolean
     */
    public function viewAny(User $user)
    {
        return false;
    }

    /**
     * Determine whether the user can view the meaning.
     *
     * @param User $user
     * @param Meaning $meaning
     * @return boolean
     */
    public function view(User $user, Meaning $meaning)
    {
        return $user->id == $meaning->user_id;
    }

    /**
     * Determine whether the user can create meanings.
     *
     * @param User $user
     * @return boolean
     */
    public function create(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can update the meaning.
     *
     * @param User $user
     * @param Meaning $meaning
     * @return boolean
     */
    public function update(User $user, Meaning $meaning)
    {
        return $user->id == $meaning->user_id;
    }

    /**
     * Determine whether the user can delete the meaning.
     *
     * @param User $user
     * @param Meaning $meaning
     * @return boolean
     */
    public function delete(User $user, Meaning $meaning)
    {
        return $user->id == $meaning->user_id;
    }

    /**
     * Determine whether the user can restore the meaning.
     *
     * @param User $user
     * @param Meaning $meaning
     * @return boolean
     */
    public function restore(User $user, Meaning $meaning)
    {
        return $user->id == $meaning->user_id;
    }

    /**
     * Determine whether the user can permanently delete the meaning.
     *
     * @param User $user
     * @param Meaning $meaning
     * @return boolean
     */
    public function forceDelete(User $user, Meaning $meaning)
    {
        return $user->id == $meaning->user_id;
    }
}
