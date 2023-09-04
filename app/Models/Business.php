<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\Models\Business
 *
 * @property int $id
 * @property string $name
 * @property string $city
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Project> $projects
 * @property-read int|null $projects_count
 * @method static \Illuminate\Database\Eloquent\Builder|Business newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Business newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Business query()
 * @method static \Illuminate\Database\Eloquent\Builder|Business whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Business whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Business whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Business whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Business whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Business extends Model
{
  use HasFactory;

  protected $table = 'businesses';
  protected $fillable = ['name', 'city'];

  public function projects(): HasMany
  {
    return $this->hasMany(Project::class);
  }
}
