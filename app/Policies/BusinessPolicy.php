<?php

namespace App\Policies;

use App\Models\Business;
use App\Models\RolesUsersObjects;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Spatie\Permission\Models\Role;

class BusinessPolicy
{
  use HandlesAuthorization;

  public function viewAny(User $user): bool
  {
    return false;
  }

  public function view(User $user, Business $business): bool
  {
    if ($user->hasRole('business-manager')) {
      if (
        RolesUsersObjects::userCan(
          $user,
          'App\Models\Business',
          $business->id,
          Role::where('name', 'business-manager')->first()->id
        )) {
        return true;
      } else {
        return false;
      }
    } else {
      return false;
    }
  }

  public function create(User $user): bool
  {
    return false;
  }

  public function update(User $user, Business $business): bool
  {
    return false;
  }

  public function delete(User $user, Business $business): bool
  {
    return false;
  }

  public function restore(User $user, Business $business): bool
  {
    return false;
  }

  public function forceDelete(User $user, Business $business): bool
  {
    return false;
  }
}
