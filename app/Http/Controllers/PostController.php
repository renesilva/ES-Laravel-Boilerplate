<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class PostController extends Controller
{
  public function index(): View
  {
    $posts = Post::all()->reverse();
    return view('blog.index', ['posts' => $posts]);
  }

  public function show($post_id, $slug): View
  {
    $post = Post::find($post_id);
    return view('blog.show', ['post' => $post]);
  }


}
