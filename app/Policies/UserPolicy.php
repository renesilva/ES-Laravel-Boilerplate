<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;

class UserPolicy
{
  public function viewAny(User $user): bool
  {
    if ($user->hasRole('super-admin')) {
      return true;
    } else {
      return false;
    }
  }

  public function view(User $user, User $model): bool
  {
    if ($user->hasRole('super-admin')) {
      return true;
    } else {
      return false;
    }
  }

  public function create(User $user): bool
  {
    if ($user->hasRole('super-admin')) {
      return true;
    } else {
      return false;
    }
  }

  public function update(User $user, User $model): bool
  {
    if ($user->hasRole('super-admin')) {
      return true;
    } else {
      return false;
    }
  }

  public function delete(User $user, User $model): bool
  {
    if ($user->hasRole('super-admin')) {
      return true;
    } else {
      return false;
    }

  }

}
