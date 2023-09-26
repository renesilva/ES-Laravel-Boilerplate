<?php

namespace App\Policies;

use App\Models\Post;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class PostPolicy
{
  public function viewAny(User $user): bool
  {
    if ($user->hasRole('super-admin')) {
      return true;
    } else if ($user->hasRole('admin')) {
      return true; // POR CONTROLADOR REVISAREMOS QUE SOLAMENTE SE DEVUELVA SUS POSTS
    } else {
      return false;
    }
  }

  public function view(User $user, Post $post): bool
  {
    if ($user->hasRole('super-admin')) {
      return true;
    } else if ($user->hasRole('admin')) {
      return $user->id === $post->user_id;
    } else {
      return false;
    }
  }

  public function create(User $user): bool
  {
    if ($user->hasRole(['admin', 'super-admin'])) {
      return true;
    } else {
      return false;
    }
  }

  public function update(User $user, Post $post): bool
  {
    if ($user->hasRole('super-admin')) {
      return true;
    } else if ($user->hasRole('admin')) {
      return $user->id === $post->user_id;
    } else {
      return false;
    }
  }

  public function delete(User $user, Post $post): bool
  {
    if ($user->hasRole('super-admin')) {
      return true;
    } else if ($user->hasRole('admin')) {
      return $user->id === $post->user_id;
    } else {
      return false;
    }
  }

}
