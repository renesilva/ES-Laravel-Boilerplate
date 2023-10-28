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
class ProductPublicTest extends TestCase
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

  public function testPublicProductsListAPI(): void
  {
    $admin = $this->newUser(['admin']);
    $responseCreateCategory = $this->createCategory($admin);
    $responseCreateCategory->assertStatus(201);

    for ($i = 0; $i < 5; $i++) {
      $responseCreate = $this->createProduct($admin, [$responseCreateCategory->json()['category']['id']]);
      $responseCreate->assertStatus(201);
    }

    $responseProducts = $this->getJson('/api/products/' . $admin->id . '/getAll');
    $responseProducts->assertStatus(200);
    $responseProducts->assertJsonStructure([
      'success',
      'products' => [
        '*' => [
          'id',
          'product_name',
          'product_description',
          'product_price',
          'product_image',
          'categories' => [
            '*' => [
              'id',
              'category_name'
            ]
          ]
        ]
      ]
    ]);
    // ToDo: falta el contar 5 productos
  }

  public function testPublicProductsListWeb(): void
  {
    $admin = $this->newUser(['admin']);
    $responseCreateCategory = $this->createCategory($admin);
    $responseCreateCategory->assertStatus(201);

    for ($i = 0; $i < 5; $i++) {
      $responseCreate = $this->createProduct($admin, [$responseCreateCategory->json()['category']['id']]);
      $responseCreate->assertStatus(201);
    }

    $responseProducts = $this->get('/products/' . $admin->id . '/getAll');
    $responseProducts->assertStatus(200);
    $responseProducts->assertViewIs('products.products');
    $responseProducts->assertViewHas('products');
    // ToDo: falta el contar 5 productos en html
  }

}
