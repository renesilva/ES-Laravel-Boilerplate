<?php

namespace App\Http\Controllers\Examples;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class FetchPostsController extends Controller
{
  /**
   * Obtiene 10 libros de https://example-data.draftbit.com/libros
   * y los guarda en la base de datos local como Posts
   * @return JsonResponse
   */
  public function fetchPostsData(): JsonResponse
  {
    $response = Http::get('https://example-data.draftbit.com/books',
      ['_limit' => 10]
    );
    if ($response->ok()) {
      foreach ($response->json() as $post) {
        $post = Post::create(
          [
            'title' => $post['title'],
            'content' => $post['description'],
            'slug' => Str::slug($post['title']),
            'user_id' => 1,
          ]
        );
      }
    }
    return response()->json([
      'success' => true,
      'message' => 'Posts created successfully',
    ])->setStatusCode(200);
  }

}
