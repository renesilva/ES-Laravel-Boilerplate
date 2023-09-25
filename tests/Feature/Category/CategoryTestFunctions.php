<?php

namespace Tests\Feature\Category;

use App\Models\User;
use Illuminate\Testing\TestResponse;

trait CategoryTestFunctions
{
  public function createCategory(User $user): TestResponse
  {
    return $this->actingAs($user)->postJson(
      '/api/categories',
      [
        'category_name' => 'Category 1',
        'category_description' => 'Category 1 description',
        'user_id' => $user->id
      ],
    );
  }

}
