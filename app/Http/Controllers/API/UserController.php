<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\RolesUsersObjects;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
  public function __construct()
  {
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
    return response()->json([
      'success' => false,
      'message' => 'You are not authorized to access this resource',
    ])->setStatusCode(401);
  }

  public function show(string $id): JsonResponse
  {
    return response()->json([
      'success' => false,
      'message' => 'You are not authorized to access this resource',
    ])->setStatusCode(401);
  }

  /**
   * Ejemplo
   *
   * {
   * "name": "abc",
   * "email": "superadmin@test.com",
   * "roles_users_objects": [
   * {
   * "role_id": 1,
   * "object_id": 1,
   * "object_class": "App\\Models\\Business"
   * }
   * ]
   * }
   *
   * @param Request $request
   * @param User $user
   * @return JsonResponse
   */
  public function update(Request $request, User $user): JsonResponse
  {
    $input = $request->only(['name', 'email', 'roles_users_objects']);
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
      //roles
      RolesUsersObjects::setRolesUsersObjects($input['roles_users_objects'], $user);
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
    return response()->json([
      'success' => false,
      'message' => 'You are not authorized to access this resource',
    ])->setStatusCode(401);
  }
}
