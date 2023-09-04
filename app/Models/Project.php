<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\Project
 *
 * @property int $id
 * @property string $name
 * @property string $city
 * @property int $budgeted_amount
 * @property int $business_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Business $business
 * @method static \Illuminate\Database\Eloquent\Builder|Project newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Project newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Project query()
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereBudgetedAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereBusinessId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Project extends Model
{
  use HasFactory;

  protected $table = 'projects';

  public function business(): BelongsTo
  {
    return $this->belongsTo(Business::class);
  }
}
