<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Item
 *
 * @property int $id
 * @property string $name
 * @property float $budgeted_price
 * @property string $comments
 * @method static \Illuminate\Database\Eloquent\Builder|Item newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Item newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Item query()
 * @mixin \Eloquent
 */
class Item extends Model
{
  use HasFactory;

  protected $table = 'item';
  protected $fillable = ['name', 'budgeted_price', 'comments'];
}
