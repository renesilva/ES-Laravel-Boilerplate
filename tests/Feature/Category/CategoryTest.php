<?php

namespace Tests\Feature\Category;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\User\UserTestFunctions;
use Tests\TestCase;

/**
 * Tests para Category
 */
class CategoryTest extends TestCase
{
  use RefreshDatabase;
  use CategoryTestFunctions;
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
   * Test para ver si un usuario Admin puede adicionar una nueva categoría
   * También revisamos si existe en la base de datos
   * @return void
   */
  public function testAdminUserCategoryCreation(): void
  {
    $admin = $this->newUser(['admin']);
    $responseCreate = $this->createCategory($admin);
    $responseCreate->assertStatus(201);
    $this->assertDatabaseHas('categories', [
      'category_name' => 'Category 1',
      'category_description' => 'Category 1 description',
    ]);
  }

  /**
   * Test para ver si un usuario Super Admin puede adicionar una nueva categoría
   *
   * @return void
   */
  public function testSuperAdminUserCategoryCreation(): void
  {
    $superAdmin = $this->newUser(['super-admin']);
    $responseCreate = $this->createCategory($superAdmin);
    $responseCreate->assertStatus(201);
  }

  /**
   * Test para ver si un usuario normal no puede agregar nuevas categorías
   *
   * @return void
   */
  public function testNormalUserCategoryCreation(): void
  {
    $normalUser = $this->newUser();
    $responseNormalUser = $this->createCategory($normalUser);
    $responseNormalUser->assertStatus(403);
  }

  /**
   * Test para ver si un usuario Admin puede editar una categoría propia
   * @return void
   */
  public function testAdminEditCategory(): void
  {
    $admin = $this->newUser(['admin']);
    $responseCreate = $this->createCategory($admin);

    $responseUpdate = $this->actingAs($admin)->json(
      'POST',
      'api/categories/' . $responseCreate->json()['category']['id'] . '?_method=PUT',
      [
        'category_name' => 'Category UPDATED NAME',
        'category_description' => 'Category UPDATED DESCRIPTION',
        'user_id' => $admin->id,
        'id' => $responseCreate->json()['category']['id']
      ]
    );

    $responseUpdate->assertStatus(200);
    $this->assertEquals('Category UPDATED NAME', $responseUpdate->json()['category']['category_name']);
    $this->assertEquals('Category UPDATED DESCRIPTION',
      $responseUpdate->json()['category']['category_description']);
  }

  /**
   * Test para ver si un usuario Admin puede eliminar una categoría propia
   * @return void
   */
  public function testAdminDeleteCategory(): void
  {
    $admin = $this->newUser(['admin']);
    $responseCreate = $this->createCategory($admin);
    $responseDelete = $this->actingAs($admin)->delete(
      'api/categories/' . $responseCreate->json()['category']['id']
    );
    $responseDelete->assertStatus(204);
  }

  /**
   * Test para ver si un Admin no puede borrar la categoría de otro Admin
   * @return void
   */
  public function testDifferentAdminDeleteCategory(): void
  {
    // admin 1 crea una categoría
    $admin1 = $this->newUser(['admin']);
    $responseCreate1 = $this->createCategory($admin1);

    // admin 2 intenta eliminar la categoría creada por admin 1
    $admin2 = $this->newUser(['admin']);
    $responseDelete = $this->actingAs($admin2)->delete(
      'api/categories/' . $responseCreate1->json()['category']['id']
    );
    $responseDelete->assertStatus(403);
  }
}
