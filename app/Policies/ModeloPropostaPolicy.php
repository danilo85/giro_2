<?php

namespace App\Policies;

use App\Models\ModeloProposta;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ModeloPropostaPolicy
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
    public function view(User $user, ModeloProposta $modeloProposta): bool
    {
        return $user->id === $modeloProposta->user_id;
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
    public function update(User $user, ModeloProposta $modeloProposta): bool
    {
        return $user->id === $modeloProposta->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, ModeloProposta $modeloProposta): bool
    {
        return $user->id === $modeloProposta->user_id;
    }

    /**
     * Determine whether the user can duplicate the model.
     */
    public function duplicate(User $user, ModeloProposta $modeloProposta): bool
    {
        return $user->id === $modeloProposta->user_id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, ModeloProposta $modeloProposta): bool
    {
        return $user->id === $modeloProposta->user_id;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, ModeloProposta $modeloProposta): bool
    {
        return $user->id === $modeloProposta->user_id;
    }
}