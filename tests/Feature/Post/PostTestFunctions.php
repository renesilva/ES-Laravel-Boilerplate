<?php

namespace Tests\Feature\Post;

use App\Models\User;
use Illuminate\Testing\TestResponse;

trait PostTestFunctions
{
  public function createPost(User $user): TestResponse
  {
    return $this->actingAs($user)->postJson(
      '/api/posts',
      [
        'title' => 'Test post',
        'content' => 'Test content',
        'slug' => 'test-title',
      ]
    );
  }

}
