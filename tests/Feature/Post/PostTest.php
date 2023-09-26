<?php

namespace Tests\Feature\Post;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\User\UserTestFunctions;
use Tests\TestCase;

class PostTest extends TestCase
{
  use RefreshDatabase;
  use PostTestFunctions;
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

  public function testAdminCreatesPost(): void
  {
    $admin = $this->newUser(['admin']);
    $responseCreate = $this->createPost($admin);
    $responseCreate->assertStatus(201);

    $this->assertDatabaseHas('posts', [
      'title' => 'Test post',
      'content' => 'Test content',
      'slug' => 'test-title',
    ]);
  }

  public function testNormalUserCreatesPost(): void
  {
    $normalUser = $this->newUser();
    $responseCreate = $this->createPost($normalUser);
    $responseCreate->assertStatus(403);
  }

  public function testAdminEditsPost(): void
  {
    $admin = $this->newUser(['admin']);
    $responseCreate = $this->createPost($admin);
    $responseCreate->assertStatus(201);
    $responseEdit = $this->actingAs($admin)->put(
      '/api/posts/' . $responseCreate->json()['post']['id'],
      [
        'title' => 'Test post edited',
        'content' => 'Test content edited',
        'slug' => 'test-title-edited',
      ]
    );
    $responseEdit->assertStatus(200);
    $this->assertDatabaseHas('posts', [
      'title' => 'Test post edited',
      'content' => 'Test content edited',
      'slug' => 'test-title-edited',
    ]);
  }

  public function testAdminDeletesPost(): void
  {
    $admin = $this->newUser(['admin']);
    $responseCreate = $this->createPost($admin);
    $responseCreate->assertStatus(201);
    $responseDelete = $this->actingAs($admin)->delete(
      '/api/posts/' . $responseCreate->json()['post']['id']
    );
    $responseDelete->assertStatus(204);
  }

  public function testDifferentAdminDeletesPost(): void
  {
    $admin1 = $this->newUser(['admin']);

    $admin2 = $this->newUser(['admin']);
    $responseCreate = $this->createPost($admin1);

    $responseDelete = $this->actingAs($admin2)->delete(
      '/api/posts/' . $responseCreate->json()['post']['id']
    );
    $responseDelete->assertStatus(403);
  }

}

