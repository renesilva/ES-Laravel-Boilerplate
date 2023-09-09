<?php
/**
 * Created by Eressea Solutions
 * @author Rene Silva <rsilva@eresseasolutions.com>
 */

namespace Eressea\MelianTaxonomy\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kodeine\Metable\Metable;


class Term extends Model
{
  use HasFactory;
  use Metable;

  public $hideMeta = true;
  protected $table = 'terms';
  public $timestamps = false;
  protected $fillable = [
    'id',
    'taxonomy_id',
    'term_name',
    'term_slug',
    'term_description',
    'parent_term_id',
    'is_active'
  ];

  public function taxonomy()
  {
    return $this->belongsTo('Eressea\MelianTaxonomy\Models\Taxonomy');
  }

  public static function prepareObjectToArray($term, $removeMetaDataArray = true)
  {
    //$term->taxonomy;//for public search
    $term = array_merge($term->toArray(), $term->getMeta()->toArray());
    if ($removeMetaDataArray) {
      unset($term['meta_data']);
      unset($term['metas']);
    }
    return $term;
  }

  public static function getChildTerms($id, $setAsKeyLabel = false)
  {
    $childTerms = self::where(
      'parent_term_id', $id
    )->get();

    $childTermsAr = [];

    foreach ($childTerms as $term) {
      if ($setAsKeyLabel) {
        $childTermsAr[] = [
          'label' => $term['term_name'],
          'value' => $term['id']
        ];
      } else {
        $childTermsAr[] = $term;
      }
    }
    return $childTermsAr;
  }

  public function toSearchableArray()
  {
    $objectArray = array_merge($this->toArray(), $this->getMeta()->toArray());
    foreach ($objectArray as $key => $value) {
      if (is_array($value)) {
        unset($objectArray[$key]);
      }
    }
    // Customize array...
    return $objectArray;
  }

}
