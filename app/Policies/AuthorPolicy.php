<?php

namespace App\Policies;

use App\Models\Autor;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class AuthorPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Autor $autor)
    {
        return $user->isAdmin() || $user->id === $autor->user_id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Autor $autor)
    {
        return $user->isAdmin() || $user->id === $autor->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Autor $autor)
    {
        return $user->isAdmin() || $user->id === $autor->user_id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Autor $autor): bool
    {
        return $user->isAdmin() || $user->id === $autor->user_id;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Autor $autor): bool
    {
        return $user->isAdmin() || $user->id === $autor->user_id;
    }
}