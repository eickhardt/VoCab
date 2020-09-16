<?php

namespace App\Policies;

use App\User;
use App\Word;
use Illuminate\Auth\Access\HandlesAuthorization;

class WordPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any words.
     *
     * @param User $user
     * @return boolean
     */
    public function viewAny(User $user)
    {
        return false;
    }

    /**
     * Determine whether the user can view the word.
     *
     * @param User $user
     * @param Word $word
     * @return boolean
     */
    public function view(User $user, Word $word)
    {
        return $user->id == $word->user_id;
    }

    /**
     * Determine whether the user can create words.
     *
     * @param User $user
     * @return boolean
     */
    public function create(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can update the word.
     *
     * @param User $user
     * @param Word $word
     * @return boolean
     */
    public function update(User $user, Word $word)
    {
        return $user->id == $word->user_id;
    }

    /**
     * Determine whether the user can delete the word.
     *
     * @param User $user
     * @param Word $word
     * @return boolean
     */
    public function delete(User $user, Word $word)
    {
        return $user->id == $word->user_id;
    }

    /**
     * Determine whether the user can restore the word.
     *
     * @param User $user
     * @param Word $word
     * @return boolean
     */
    public function restore(User $user, Word $word)
    {
        return $user->id == $word->user_id;
    }

    /**
     * Determine whether the user can permanently delete the word.
     *
     * @param User $user
     * @param Word $word
     * @return boolean
     */
    public function forceDelete(User $user, Word $word)
    {
        return $user->id == $word->user_id;
    }
}
