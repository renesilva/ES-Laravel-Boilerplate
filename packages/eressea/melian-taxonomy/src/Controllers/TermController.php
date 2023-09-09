<?php
/**
 * Created by Eressea Solutions
 * @author Rene Silva <rsilva@eresseasolutions.com>
 */

namespace Eressea\MelianTaxonomy\Controllers;

use Eressea\MelianHelpers\Helpers\FileUploadHelper;
use App\Http\Controllers\Controller;
use Eressea\MelianTaxonomy\Models\Taxonomy;
use Eressea\MelianTaxonomy\Models\Term;
use Eressea\MelianTaxonomy\Models\TaxonomyFieldRelationship;
use Eressea\MelianTaxonomy\Models\TaxonomyFieldRelationshipObject;


class TermController extends Controller
{
  public function getTerms($tax_slug)
  {
    $taxonomy = Taxonomy::where('taxonomy_slug', $tax_slug)
      ->with('terms')
      ->first();
    if ($taxonomy) {
      return response()->json([
        'success' => true,
        'taxonomy' => $taxonomy->toArray()
      ]);
    }
  }

  public function getRels($tax_slug, $term_slug)
  {
    $term = Term::with('taxonomy')->where('term_slug', $term_slug)->first();

    if ($term) {
      $taxonomy = $term->taxonomy;
      $taxRelsObjs = TaxonomyFieldRelationship::where('taxonomy_id', $taxonomy->id)->get();

      $sections = [];

      foreach ($taxRelsObjs as $taxRelObj) {
        $photos = [];
        $page = '';///
        $objects = TaxonomyFieldRelationshipObject::getObjectsFromTerm(
          $term,
          $taxRelObj->taxonomy_field_relationship_object_class,
          $taxRelObj->taxonomy_field_relationship_slug, true, 3);


        switch ($taxRelObj->taxonomy_field_relationship_object_class) {
          case 'App\Models\User':
            $photos = FileUploadHelper::getUsersPhotos($objects);
            $page = 'peopleList';///
            break;
        }

        if (!empty($objects)) {
          $sections[] = [
            'title' => sprintf($taxRelObj->taxonomy_field_relationship_name, $term->term_name),
            'description' => '',
            'photos' => $photos,
            'tabName' => '',
            'section' => '',
            'page' => $page,
            'params' => [
              'taxonomyRelationshipSlug' => $taxRelObj->taxonomy_field_relationship_slug,
              'termSlug' => $term->term_slug,
              'objectType' => $taxRelObj->taxonomy_field_relationship_object_class
            ]
          ];
        }
      }

      return response()->json([
        'success' => true,
        'term' => $term->toArray(),
        'sections' => $sections
      ]);

    } else {
      return response()->json(['success' => false]);
    }

  }
}
