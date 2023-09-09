<?php
/**
 * Created by Eressea Solutions
 * @author Rene Silva <rsilva@eresseasolutions.com>
 */

namespace Eressea\MelianTaxonomy\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kodeine\Metable\Metable;

class TaxonomyFieldRelationship extends Model
{
  use HasFactory;
  use Metable;

  protected $table = 'taxonomy_field_relationships';
  public $timestamps = false;
  protected $fillable = [
    'id',
    'taxonomy_id',
    'taxonomy_field_relationship_object_class',
    'taxonomy_field_relationship_name',
    'taxonomy_field_relationship_slug',
    'taxonomy_field_relationship_description',
    'is_active'
  ];

  public function taxonomy()
  {
    return $this->belongsTo('Eressea\MelianTaxonomy\Models\Taxonomy');
  }
}
