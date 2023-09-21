<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

/**
 * Esta clase administra usuarios.
 */
class UserController extends Controller
{
  public function __construct()
  {
    // Solamente el super-admin puede acceder a este controlador
    $this->middleware(['role:super-admin']);
  }

  public function index(): JsonResponse
  {
    $users = User::all();
    return response()->json([
      'success' => true,
      'data' => $users,
    ]);
  }

  public function store(Request $request): JsonResponse
  {
    $input = $request->only(['name', 'email', 'password']);
    $validator = Validator::make($input, [
      'name' => 'required',
      'email' => 'required',
      'password' => 'required',
    ]);
    if ($validator->fails()) {
      // falla
      return response()->json([
        'success' => false,
        'message' => 'Validation error',
        'errors' => $validator->errors(),
      ])->setStatusCode(400);
    } else {
      // no falla
      $user = User::create($input);
      if ($input['password'] != '') {
        $user->password = Hash::make(json_decode($input['password']));
        $user->save();
      } else {
        return response()->json([
          'success' => false,
          'message' => 'Password is required'
        ])->setStatusCode(400);
      }
      return response()->json([
        'success' => true,
        'user' => $user,
      ]);
    }
  }

  public function show(string $id): JsonResponse
  {
    $user = User::find($id);
    if (is_null($user)) {
      return response()->json([
        'success' => false,
        'message' => 'User not found',
      ])->setStatusCode(404);
    } else {
      return response()->json([
        'success' => true,
        'user' => $user,
      ]);
    }
  }


  public function update(Request $request, User $user): JsonResponse
  {
    $input = $request->only(['name', 'email']);
    $validator = Validator::make($input, [
      'name' => 'required',
      'email' => 'email|required',
    ]);
    if ($validator->fails()) {
      return response()->json([
        'success' => false,
        'message' => 'Validation error',
        'errors' => $validator->errors(),
      ])->setStatusCode(400);
    } else {
      // check if email is unique
      $user_with_email = User::where('email', $input['email'])->first();
      if ($user_with_email) {
        if ($user_with_email->id != $user->id) {
          return response()->json([
            'success' => false,
            'message' => 'Validation error',
            'errors' => ['email' => ['The email has already been taken.']],
          ])->setStatusCode(422);
        }
      }
      //fields
      $user->name = $input['name'];
      $user->email = $input['email'];
      //save
      $user->save();
      $user->refresh();
      return response()->json([
        'success' => true,
        'message' => 'User updated successfully',
        'user' => $user,
      ]);
    }
  }

  public function destroy(User $user): JsonResponse
  {
    $user->delete();
    return response()->json([
      'success' => true,
      'message' => 'User deleted',
    ]);
  }

}
