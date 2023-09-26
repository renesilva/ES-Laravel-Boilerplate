<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CommentController extends Controller
{
  public function store(Request $request)
  {
    $input = $request->only(['name', 'comment', 'post_id']);
    $validator = Validator::make($input, [
      'name' => 'required',
      'comment' => 'required',
      'post_id' => 'required',
    ]);
    if ($validator->fails()) {
      return response()->json([
        'success' => false,
        'message' => 'Validation error',
        'errors' => $validator->errors(),
      ])->setStatusCode(400);
    } else {
      $comment = Comment::create($input);
      return redirect('/blog/' . $comment->post_id . '/' . $comment->post->slug);
    }
  }
}
