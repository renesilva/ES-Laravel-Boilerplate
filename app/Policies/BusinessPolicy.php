<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class BusinessPolicy
{
  use HandlesAuthorization;

  public function viewAny(User $user)
  {
    //
  }

  public function view(User $user)
  {
    //
  }
}
