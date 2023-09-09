<?php
/**
 * Created by Eressea Solutions
 * @author Rene Silva <rsilva@eresseasolutions.com>
 */

namespace Eressea\MelianTaxonomy\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Kodeine\Metable\Metable;

class Taxonomy extends Model
{
  use HasFactory;
  use Metable;

  public $hideMeta = true;
  protected $table = 'taxonomies';
  public $timestamps = false;
  protected $fillable = [
    'id',
    'taxonomy_name',
    'taxonomy_slug',
    'taxonomy_description',
    'hierarchy',
    'is_active'
  ];

  public function terms(): HasMany
  {
    // Only parent terms
    return $this->hasMany('Eressea\MelianTaxonomy\Models\Term')
      ->where('terms.parent_term_id', 0)/////!!!
      ->orderBy('terms.term_name');
  }

  public function fieldRelationships()
  {
    return $this->hasMany('Eressea\MelianTaxonomy\Models\TaxonomyFieldRelationship');
  }

  public static function loadTaxonomiesJSChosen()
  {
    $taxonomies = [];
    foreach (Taxonomy::orderBy('taxonomy_name', 'asc')->get() as $taxonomy) {
      $taxonomies[] = [
        'label' => $taxonomy->taxonomy_name,
        'value' => $taxonomy->id,
      ];
    }

    return $taxonomies;
  }
}
