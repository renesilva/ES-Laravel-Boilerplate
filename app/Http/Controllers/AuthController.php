<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
  public function __construct()
  {
    $this->middleware('auth:api', ['except' => ['login', 'refresh']]);
  }

  public function login(Request $request): JsonResponse
  {
    $request->validate([
      'email' => 'required|string|email',
      'password' => 'required|string',
    ]);
    $credentials = request(['email', 'password']);
    $token = Auth::attempt($credentials);
    if (!$token) {
      return response()->json([
        'success' => false,
        'message' => 'Unauthorized'
      ])->setStatusCode(401);
    }

    $user = Auth::user();
    return response()->json([
      'success' => true,
      'user' => $user,
      'token' => $token
    ]);
  }

  public function logout(): JsonResponse
  {
    Auth::logout();
    return response()->json([
      'success' => true,
      'message' => 'Successfully logged out'
    ]);
  }

  public function refresh(): JsonResponse
  {
    return response()->json([
      'success' => true,
      'token' => Auth::refresh()
    ]);
  }

}

