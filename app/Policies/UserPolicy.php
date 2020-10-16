<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }
    //更新用户信息，操作用户必须与当前用户一致
    public function update(User $currentUser, User $user)
    {
        return $currentUser->fillable(['id']) === $user->fillable(['id']);
    }

    //更新用户信息，操作用户必须与当前用户一致
    public function destroy(User $currentUser, User $user)
    {
        return $currentUser->id !== $user->id && $currentUser->is_admin;
    }
}
