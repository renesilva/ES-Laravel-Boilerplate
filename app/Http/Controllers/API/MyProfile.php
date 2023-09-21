<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MyProfile extends Controller
{
  public function getProfile(): JsonResponse
  {
    $user = User::find(Auth::user()->id);
    $roles = $user->roles;

    return response()->json([
      'success' => true,
      'user' => $user,
      'roles' => $roles,
    ]);
  }
}
