<?php

namespace Tests\Feature\Product;

use App\Models\Category;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Testing\TestResponse;

trait ProductTestFunctions
{
  public function createProduct(User $user, array $categories_ids = []): TestResponse
  {
    Storage::fake('images');
    return $this->actingAs($user)->postJson(
      '/api/products',
      [
        'product_name' => 'Product 1',
        'product_description' => 'Product 1 description',
        'product_price' => 100,
        'product_image' => UploadedFile::fake()->image('product_image.jpg'),
        'categories' => $categories_ids,
        'user_id' => $user->id
      ],
    );
  }

}
