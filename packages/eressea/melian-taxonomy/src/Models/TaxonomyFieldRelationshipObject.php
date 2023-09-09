<?php
/**
 * Created by Eressea Solutions
 * @author Rene Silva <rsilva@eresseasolutions.com>
 */

namespace Eressea\MelianTaxonomy\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Eressea\MelianTaxonomy\Models\TaxonomyFieldRelationship;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Database\Eloquent\SoftDeletes;

class TaxonomyFieldRelationshipObject extends Model
{

  use HasFactory;
  use SoftDeletes;

  protected $table = 'taxonomy_field_relationship_objects';

  protected $fillable = [
    'id',
    'taxonomy_field_relationship_id',
    'term_id',
    'object_id'
  ];

  /**
   * Get object terms
   * @param int $id_object
   * @param string $relationship
   * @param boolean $idsOnly
   * @param boolean $setKeys
   * @param boolean $setAsKeyLabel
   * @return terms[]
   */
  public static function getTerms(
    int $id_object, string $relationship, bool $idsOnly = false, bool $setKeys = false, bool $setAsKeyLabel = false
  ): array
  {
    $terms = [];

    $taxrel = TaxonomyFieldRelationship::where(
      'taxonomy_field_relationship_slug', $relationship)->first();

    foreach (self::
    where([
      ['taxonomy_field_relationship_id', '=', $taxrel->id],
      ['object_id', '=', $id_object]
    ])->cursor() as $tfros) {
      if ($idsOnly) {
        //"taxonomy_field_relationship_object_id" => "term_id"
        if ($setKeys) {
          $terms[$tfros->id] = $tfros->term_id;
        } else {
          $terms[] = $tfros->term_id;
        }
      } else {
        //ToDo: evaluate if this change has any impact on the performance of the app
        $termObject = Term::with(['taxonomy'])->find($tfros->term_id);
        if ($setAsKeyLabel) {
          $terms[] = [
            'label' => $termObject->term_name,
            'key' => $termObject->id,
          ];
        } else {
          $terms[] = $termObject;
        }
      }
    }

    return $terms;
  }

  public static function getFirstTerm($id_object, $relationship)
  {
    $term = null;
    $terms = self::getTerms($id_object, $relationship);
    if (!empty($terms)) {
      return array_pop($terms);
    } else {
      return $term;
    }
  }

  /**
   * Get objects from term
   * @param Term $term
   * @param string $object_class
   * @param string $relationship
   * @param boolean $shuffle
   * @param int $slice
   * @param boolean $prepareArray
   * @param string $sortBy
   * @param string $order
   * @param boolean $allow_anonymous_all_objects
   * @param string $class_term_str Filters by program (GCL, ILG)
   * @return array
   */
  public static function getObjectsFromTerm(
    Term   $term,
    string $object_class,
    string $relationship,
    bool   $shuffle = false,
    int    $slice = 0,
    bool   $prepareArray = false, //get photos and other relationships
    string $sortBy = '',
    string $order = 'ASC',
    bool   $allow_anonymous_all_objects = false,
    string $class_term_str = ''
  ): array
  {
    $objects = [];
    $taxrel = TaxonomyFieldRelationship::where(
      'taxonomy_field_relationship_slug', $relationship)->first();

    $i = 0;
    foreach (self::
    where([
      ['taxonomy_field_relationship_id', '=', $taxrel->id],
      ['term_id', '=', $term->id]
    ])->orderBy('created_at', 'asc')->cursor() as $tfros) {

      try {
        $object = $object_class::findOrFail($tfros->object_id);
      } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $exception) {
        return response()->json(['problematic_id' => $tfros->object_id]);
      }
      if ($allow_anonymous_all_objects) {
        $objects[] = $object;
        $i++;
      } else if (User::usersInTheSameGroup($current_user, $object->user_id)) {
        //filters by program
        if ($class_term_str != '' && isset($object->data)) {
          if (str_contains($object->data, $class_term_str)) {
            $objects[] = $object;
            $i++;
          }
        } else {
          $objects[] = $object;
          $i++;
        }
      }

      if ($slice) {
        if ($i >= $slice) {
          break;
        }
      }
    }

    if ($shuffle) {
      shuffle($objects);
    }

    if ($prepareArray) {
      foreach ($objects as $key => $object) {
        $className = get_class($object);
        $objects[$key] = $className::prepareObjectToArray($object);
      }
    }

    if ($sortBy != '') {
      usort($objects, function ($item1, $item2) use ($sortBy, $order) {
        if ($order == 'ASC') {
          return trim($item1[$sortBy]) <=> trim($item2[$sortBy]);
        } else if ($order == 'DESC') {
          return trim($item2[$sortBy]) <=> trim($item1[$sortBy]);
        }

      });
    }

    return $objects;
  }

  /**
   * Set terms of an object
   * @param object $object
   * @param string[] $relationships
   * @param object $input
   * @param boolean $forceDelete
   */
  public static function setObjectsTerms(
    object $object,
    array  $relationships,
    array  $input,
    bool   $forceDelete = false
  ): void
  {
    foreach ($relationships as $relationship) {
      //field is set
      if (isset($input[$relationship])) {

        $current_values = $object->{$relationship} ?? [];

        $values = array();
        if (is_array($input[$relationship])) {
          $values = $input[$relationship];
        } elseif (is_numeric($input[$relationship])) {
          $values = [$input[$relationship]];
        }

        $ar_values_intersect = array_intersect($current_values, $values);

        //to delete
        $ar_values_delete = array_diff($current_values, $ar_values_intersect);
        if (!empty($ar_values_delete)) {
          $ar_keys_delete = array_keys($ar_values_delete);
          foreach ($ar_keys_delete as $key_delete) {
            $tfro = TaxonomyFieldRelationshipObject::find($key_delete);
            if ($forceDelete) {
              $tfro->forceDelete();
            } else {
              $tfro->delete();
            }
          }
        }

        //to add
        $ar_values_add = array_diff($values, $ar_values_intersect);
        if (!empty($ar_values_add)) {
          $fieldRelObj = TaxonomyFieldRelationship::where('taxonomy_field_relationship_slug', $relationship)->first();
          foreach ($ar_values_add as $value_to_add) {
            if ($value_to_add != 0) {
              $objExist = self::withTrashed()
                ->where([
                  ['taxonomy_field_relationship_id', '=', $fieldRelObj->id],
                  ['term_id', '=', $value_to_add],
                  ['object_id', '=', $object->id],
                ])
                ->first();
              if ($objExist && $objExist->trashed()) {
                $objExist->restore();
              } else {
                $tfro = new TaxonomyFieldRelationshipObject([
                  'taxonomy_field_relationship_id' => $fieldRelObj->id,
                  'term_id' => $value_to_add,
                  'object_id' => $object->id
                ]);
                $tfro->save();
              }
            }
          }
        }

      }
    }
  }

}
