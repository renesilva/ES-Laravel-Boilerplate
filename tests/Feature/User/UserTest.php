<?php

namespace Tests\Feature\User;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{
  use RefreshDatabase;
  use UserTestFunctions;

  public function setUp(): void
  {
    parent::setUp();
    $this->initDatabase();
  }

  public function tearDown(): void
  {
    $this->resetDatabase();
    parent::tearDown();
  }

  /**
   * Prueba que el super-admin pueda crear un usuario.
   * @return void
   */
  public function testSuperAdminCanCreateUser(): void
  {
    $superAdmin = $this->newUser(['super-admin']);
    $responseCreateUser = $this->createUser($superAdmin);
    $responseCreateUser->assertStatus(201);

    $this->assertDatabaseHas('users', [
      'name' => 'New user',
      'email' => 'new@test.com',
    ]);
  }

  // Prueba que un usuario normal no pueda crear un usuario.
  public function testNormalUserCantCreateUser(): void
  {
    $normalUser = $this->newUser();
    $responseCreateUser = $this->createUser($normalUser);
    $responseCreateUser->assertStatus(403);
  }

  public function testCantHave2UsersWithTheSameEmail(): void
  {
    $superAdmin = $this->newUser(['super-admin']);
    $responseCreateUser = $this->createUser($superAdmin);
    $responseCreateUser->assertStatus(201);
    $responseCreateUser2 = $this->createUser($superAdmin);
    $responseCreateUser2->assertStatus(422);
  }

  public function testSuperAdminCanEditUser(): void
  {
    $superAdmin = $this->newUser(['super-admin']);
    $responseCreateUser = $this->createUser($superAdmin);

    $responseUpdate = $this->actingAs($superAdmin)->putJson(
      '/api/users/' . $responseCreateUser->json()['user']['id'],
      [
        'name' => 'New user updated',
        'email' => 'new@test.com',
      ]
    );
    $responseUpdate->assertStatus(200);
    $this->assertDatabaseHas('users', [
      'name' => 'New user updated',
      'email' => 'new@test.com',
    ]);
  }

  public function testSuperAdminEditUserDuplicateEmail(): void
  {
    $superAdmin = $this->newUser(['super-admin']);
    $responseCreateUser = $this->createUser($superAdmin);
    $responseUpdate = $this->actingAs($superAdmin)->putJson(
      '/api/users/' . $responseCreateUser->json()['user']['id'],
      [
        'name' => 'New user updated',
        'email' => 'test@test.com', // este usuario ya existe al correr las migraciones
      ]
    );
    $responseUpdate->assertStatus(422);
  }

  // Prueba que el super-admin pueda borrar un usuario.
  public function testSuperAdminCanDeleteUser(): void
  {
    $superAdmin = $this->newUser(['super-admin']);
    $responseCreateUser = $this->createUser($superAdmin);

    $responseDelete = $this->actingAs($superAdmin)->deleteJson(
      '/api/users/' . $responseCreateUser->json()['user']['id']
    );
    $responseDelete->assertStatus(204);
  }
}

