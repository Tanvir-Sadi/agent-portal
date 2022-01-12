<?php

namespace App\Policies;

use App\Models\University;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UniversityPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\University  $university
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, University $university)
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        
        return $user->roles == 'admin';
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\University  $university
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, University $university)
    {
        return $user->roles === 'admin';
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\University  $university
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, University $university)
    {
        return $user->roles === 'admin';
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\University  $university
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, University $university)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\University  $university
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, University $university)
    {
        return $user->roles === 'admin';
    }
}
