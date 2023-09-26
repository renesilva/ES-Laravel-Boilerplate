<?php

namespace Tests\Feature\Comment;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CommentTest extends TestCase
{
  use RefreshDatabase;

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

  public function testNewComment()
  {
    $post = Post::factory()
      ->for(User::factory()->state([
        'name' => 'Jessica Archer',
      ]))
      ->create();
    $this->assertDatabaseHas('posts', [
      'title' => $post->title,
      'content' => $post->content,
      'slug' => $post->slug,
    ]);
    $this->assertDatabaseHas('users', [
      'name' => 'Jessica Archer',
    ]);
    $response = $this->postJson(
      '/comments',
      [
        'name' => 'Jessica Archer',
        'comment' => 'Comentario de prueba',
        'post_id' => $post->id,
      ]
    );
    $response->assertRedirectContains(
      '/blog/' . $post->id . '/' . $post->slug);
    $this->assertDatabaseHas('comments', [
      'name' => 'Jessica Archer',
      'comment' => 'Comentario de prueba',
      'post_id' => $post->id,
    ]);
  }

}

