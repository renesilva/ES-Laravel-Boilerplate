<?php

namespace Tests\Feature\User;

use App\Models\User;
use Illuminate\Testing\TestResponse;

trait UserTestFunctions
{
  public function newUser($roles = []): User
  {
    $user = User::factory()->create();
    if (!empty($roles)) {
      foreach ($roles as $role) {
        $user->assignRole($role);
      }
    }
    return $user;
  }

  public function createUser(User $user): TestResponse
  {
    return $this->actingAs($user)->postJson(
      '/api/users',
      [
        'name' => 'New user',
        'email' => 'new@test.com',
        'password' => 'password',
      ],
    );
  }
}
