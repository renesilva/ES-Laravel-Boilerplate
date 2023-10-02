<?php

namespace Tests\Feature\Product;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\Feature\Category\CategoryTestFunctions;
use Tests\Feature\User\UserTestFunctions;
use Tests\TestCase;

/**
 * Tests para Category
 */
class ProductTest extends TestCase
{
  use RefreshDatabase;
  use ProductTestFunctions;
  use UserTestFunctions;
  use CategoryTestFunctions;

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
   * Test para ver si un usuario Admin puede adicionar un nuevo producto
   * También revisamos si existe en la base de datos
   * @return void
   */
  public function testAdminUserProductCreation(): void
  {
    $admin = $this->newUser(['admin']);
    $responseCreateCategory = $this->createCategory($admin);
    $responseCreateCategory->assertStatus(201);
    $responseCreate = $this->createProduct($admin, [$responseCreateCategory->json()['category']['id']]);
    // Revisamos respuesta 201
    $responseCreate->assertStatus(201);
    // Revisamos que se encuentre en la tabla
    $this->assertDatabaseHas('products', [
      'product_name' => 'Product 1',
      'product_description' => 'Product 1 description',
      'product_price' => 100,
    ]);
    // Revisamos sis existe el archivo en el disco
    Storage::disk('images')->assertExists(
      'public/products/images/' . $responseCreate->json()['product']['product_image']
    );
  }

  /**
   * Test para ver si un usuario Super Admin puede adicionar un nuevo producto
   *
   * @return void
   */
  public function testSuperAdminUserProductCreation(): void
  {
    $superAdmin = $this->newUser(['super-admin']);
    $responseCreateCategory = $this->createCategory($superAdmin);
    $responseCreateCategory->assertStatus(201);
    $responseCreate = $this->createProduct($superAdmin, [$responseCreateCategory->json()['category']['id']]);
    $responseCreate->assertStatus(201);
  }

  /**
   * Test para ver si un usuario normal no puede agregar nuevos productos
   *
   * @return void
   */
  public function testNormalUserProductCreation(): void
  {
    $normalUser = $this->newUser();
    $responseNormalUser = $this->createProduct($normalUser, []);
    $responseNormalUser->assertStatus(403);
  }

  /**
   * Test para ver si un usuario Admin puede editar un producto propio
   * @return void
   */
  public function testAdminEditProduct(): void
  {
    $admin = $this->newUser(['admin']);
    $responseCreateCategory = $this->createCategory($admin);
    $responseCreateCategory->assertStatus(201);
    $responseCreate = $this->createProduct($admin, [$responseCreateCategory->json()['category']['id']]);

    $responseUpdate = $this->actingAs($admin)->json(
      'POST',
      'api/products/' . $responseCreate->json()['product']['id'] . '?_method=PUT',
      [
        'product_name' => 'Product UPDATED NAME',
        'product_description' => 'Product UPDATED DESCRIPTION',
        'product_price' => 100,
        'product_image' => UploadedFile::fake()->image('product_image2.jpg'),
        'user_id' => $admin->id,
        'categories' => [],
        'id' => $responseCreate->json()['product']['id']
      ]
    );
    // Revisamos respuesta 200
    $responseUpdate->assertStatus(200);
    // Revisamos cambio de nombre
    $this->assertEquals('Product UPDATED NAME',
      $responseUpdate->json()['product']['product_name']);
    // Revisamos cambio de descripción
    $this->assertEquals('Product UPDATED DESCRIPTION',
      $responseUpdate->json()['product']['product_description']);
    // Revisamos que el archivo se encuentre subido
    Storage::disk('images')->assertExists(
      'public/products/images/' . $responseCreate->json()['product']['product_image']
    );
  }

  /**
   * Test para ver si un usuario Admin puede eliminar un producto propio
   * @return void
   */
  public function testAdminDeleteProduct(): void
  {
    $admin = $this->newUser(['admin']);
    $responseCreate = $this->createProduct($admin);
    $responseCreate->assertStatus(201);
    $responseDelete = $this->actingAs($admin)->delete(
      'api/products/' . $responseCreate->json()['product']['id']
    );
    $responseDelete->assertStatus(204);
  }

  /**
   * Test para ver si un Admin no puede borrar el producto de otro Admin
   * @return void
   */
  public function testDifferentAdminDeleteProduct(): void
  {
    // admin 1 crea una producto
    $admin1 = $this->newUser(['admin']);
    $responseCreate1 = $this->createProduct($admin1);
    $responseCreate1->assertStatus(201);

    // admin 2 intenta eliminar otro producto creada por admin 1
    $admin2 = $this->newUser(['admin']);
    $responseDelete = $this->actingAs($admin2)->delete(
      'api/products/' . $responseCreate1->json()['product']['id']
    );
    $responseDelete->assertStatus(403);
  }
}
