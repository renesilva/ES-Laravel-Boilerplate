<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{

  public function __construct()
  {
    $this->authorizeResource(Post::class, 'post');
  }

  /**
   * El super-admin puede ver todos los posts, el autor solamente los suyos
   * @return JsonResponse
   */
  public function index(): JsonResponse
  {
    $currentUser = User::find(Auth::user()->id);
    // para super-admin
    if ($currentUser->hasRole('super-admin')) {
      return response()->json([
        'success' => true,
        'message' => 'Posts fetched successfully',
        'posts' => Post::all()
      ]);
    }
    // para admin
    $posts = Post::where('user_id', $currentUser->id)->get();
    return response()->json([
      'success' => true,
      'message' => 'Posts fetched successfully',
      'posts' => $posts
    ]);
  }

  public function store(Request $request): JsonResponse
  {
    $input = $request->only(['title', 'content', 'slug']);
    $validator = Validator::make($input, [
      'title' => 'required',
      'content' => 'required',
      'slug' => 'required',
    ]);
    if ($validator->fails()) {
      // falla
      return response()->json([
        'success' => false,
        'message' => 'Validation error',
        'errors' => $validator->errors(),
      ])->setStatusCode(400);
    } else {
      // no falla
      $input['user_id'] = Auth::user()->id;
      $post = Post::create($input);
      return response()->json([
        'success' => true,
        'post' => $post,
      ])->setStatusCode(201);
    }
  }

  public function show(Post $post): JsonResponse
  {
    return response()->json([
      'success' => true,
      'message' => 'Post fetched successfully',
      'post' => $post,
    ]);
  }


  public function update(Request $request, Post $post): JsonResponse
  {
    $input = $request->only(['title', 'content', 'slug']);
    $validator = Validator::make($input, [
      'title' => 'required',
      'content' => 'required',
      'slug' => 'required',
    ]);
    if ($validator->fails()) {
      // falla
      return response()->json([
        'success' => false,
        'message' => 'Validation error',
        'errors' => $validator->errors(),
      ])->setStatusCode(400);
    } else {
      // no falla
      $input['user_id'] = Auth::user()->id;
      $post->update($input);
      return response()->json([
        'success' => true,
        'post' => $post,
        'message' => 'Post updated successfully',
      ])->setStatusCode(200);
    }
  }

  public function destroy(Post $post): JsonResponse
  {
    $post->delete();
    return response()->json([
      'success' => true,
      'message' => 'Post deleted successfully',
    ])->setStatusCode(204);
  }
}
