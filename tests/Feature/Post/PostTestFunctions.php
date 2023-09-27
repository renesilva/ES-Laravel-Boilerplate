<?php

namespace Tests\Feature\Post;

use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Testing\TestResponse;

trait PostTestFunctions
{
  public function createPost(User $user): TestResponse
  {
    Storage::fake('public/posts/images');
    return $this->actingAs($user)->json(
      'POST',
      '/api/posts',
      [
        'title' => 'Test post',
        'content' => 'Test content',
        'slug' => 'test-title',
        'thumbnail' => UploadedFile::fake()->image('thumbnail.jpg')
      ]
    );
  }

}
