<?php
/**
 * Created by Eressea Solutions
 * @author Rene Silva <rsilva@eresseasolutions.com>
 */

namespace Eressea\MelianTaxonomy\Traits;

use Eressea\MelianTaxonomy\Models\TaxonomyFieldRelationshipObject;
use Eressea\MelianTaxonomy\Models\TaxonomyFieldRelationship;

trait RelationshipTrait
{
  /**
   * @param $arRel
   * @param bool $idsOnly
   * @param bool $setKeys Display terms like TFRO_ID -> TERM_ID
   * @param bool $setAsKeyLabel Display terms as key label array
   */
  public function loadRelationships(
    $arRel, bool $idsOnly = false, bool $setKeys = false, bool $setAsKeyLabel = false): void
  {
    foreach ($arRel as $rel) {
      $this->{$rel} = TaxonomyFieldRelationshipObject::getTerms($this->id, $rel, $idsOnly, $setKeys, $setAsKeyLabel);
    }
  }

  public static function returnFields($sectionsGet, bool $return_all_in_array = false): array
  {
    $sectionsReturn = [];

    $calledClass = get_called_class();

    if (isset($calledClass::$sectionFieldsModelName)) {//If UserProject => User
      $className = $calledClass::$sectionFieldsModelName;
    } else {
      $classNameAr = explode("\\", get_called_class());
      $className = $classNameAr[sizeof($classNameAr) - 1];
    }

    //precviously set to self::$sections
    $sections = (include app_path('Models/SectionFields/' . $className . '.php'));

    if ($return_all_in_array) {
      //return all fields in one array
      foreach ($sectionsGet as $sectionGet) {
        $sectionsReturn = array_merge($sectionsReturn, $sections[$sectionGet]);
      }
    } else {
      //return fields by sections
      foreach ($sectionsGet as $sectionGet) {
        $sectionsReturn[$sectionGet] = $sections[$sectionGet];
      }
    }
    return $sectionsReturn;

  }

  public static function returnTaxRelFieldsData($fieldRels): array
  {
    $taxAndTerms = [];
    foreach ($fieldRels as $fieldRel) {
      $terms = TaxonomyFieldRelationship::where('taxonomy_field_relationship_slug', $fieldRel)->
      first()->taxonomy->terms;

      $taxAndTerms[$fieldRel] = [];

      foreach ($terms as $term) {
        $taxAndTerms[$fieldRel][] = ['value' => $term->id, 'label' => $term->term_name];
      }
    }
    return $taxAndTerms;
  }
}
