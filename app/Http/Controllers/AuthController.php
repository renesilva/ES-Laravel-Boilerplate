<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class AuthController extends Controller
{
  public function __construct()
  {
    $this->middleware('auth:api',
      [
        'except' => ['login', 'refresh', 'forgotPassword', 'passwordReset']
      ]);
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
      'token' => $token,
      'refresh_token' => Auth::refresh()
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

  public function forgotPassword(Request $request): JsonResponse
  {
    $input = $request->only(['email']);
    $validator = Validator::make($input, [
      'email' => 'required|email',
    ]);
    if ($validator->fails()) {
      return response()->json([
        'success' => false,
        'message' => 'Validation error',
        'errors' => $validator->errors(),
      ])->setStatusCode(400);
    } else {
      $status = Password::sendResetLink($input);
      if ($status === Password::RESET_LINK_SENT) {
        return response()->json([
          'success' => true,
          'message' => 'Reset link sent to your email.',
        ]);
      } else {
        return response()->json([
          'success' => false,
          'message' => 'Unable to send reset link',
        ])->setStatusCode(422);
      }
    }
  }

  public function passwordReset(Request $request)
  {
    $input = $request->only(['email', 'token', 'password', 'password_confirmation']);
    $validator = Validator::make($input, [
      'token' => 'required',
      'email' => 'required|email',
      'password' => 'required|min:8|confirmed',
    ]);
    if ($validator->fails()) {
      return view('auth.password_reset', [
        'token' => $input['token'],
        'errors' => $validator->errors()
      ]);
    } else {
      $status = Password::reset(
        $request->only('email', 'password', 'password_confirmation', 'token'),
        function (User $user, string $password) {
          $user->forceFill([
            'password' => Hash::make($password)
          ])->setRememberToken(Str::random(60));

          $user->save();

          event(new PasswordReset($user));
        }
      );
      if ($status === Password::PASSWORD_RESET) {
        return view('auth.password_reset', [
          'successMessages' => ['Successfully changed password!'],
          'token' => '',
        ]);
      } else {
        return view('auth.password_reset', [
          'token' => $input['token'],
          'errors' => $validator->errors()->add(
            'password', 'Unable to set new password'
          )
        ]);
      }
    }
  }

}

