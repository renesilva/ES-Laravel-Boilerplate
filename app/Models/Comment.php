<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\Comment
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Comment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Comment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Comment query()
 * @property-read \App\Models\Post|null $post
 * @mixin \Eloquent
 */
class Comment extends Model
{
  use HasFactory;

  protected $table = 'comments';
  protected $fillable = [
    'name',
    'comment',
    'post_id',
  ];


  public function post(): BelongsTo
  {
    return $this->belongsTo(Post::class);
  }
}
