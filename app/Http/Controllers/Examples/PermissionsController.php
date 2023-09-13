<?php

namespace App\Http\Controllers\Examples;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PermissionsController extends Controller
{
  public function assignRoleUser(): JsonResponse
  {
    $user = Auth::user();
    $currentUser = User::find($user->id);
    $currentUser->assignRole('superadmin');
    return response()->json([
      'success' => true,
      'message' => 'Role assigned',
      'roles' => $currentUser->roles
    ]);
  }

  public function getRoleUser(): JsonResponse
  {
    $user = Auth::user();
    return response()->json([
      'success' => true,
      'message' => 'Role assigned',
      'roles' => $user->roles
    ]);
  }
}
