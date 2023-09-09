<?php

namespace App\Http\Controllers\Examples;

use App\Http\Controllers\Controller;
use App\Models\User;
use Eressea\MelianTaxonomy\Models\TaxonomyFieldRelationshipObject;
use Eressea\MelianTaxonomy\Models\Term;
use Illuminate\Http\Request;

class TermsController extends Controller
{
  public function addTermToUser($id): void
  {
    $user = User::find($id);
    $fieldRels = User::returnFields(['tax_rel'], true);
    $user->loadRelationships($fieldRels, true, true);
    TaxonomyFieldRelationshipObject::setObjectsTerms($user, $fieldRels, [
      'user-location' => 1
    ]);
  }

  public function getTermsUser($id)
  {
    $user = User::find($id);
    $fieldRels = User::returnFields(['tax_rel'], true);
    $user->loadRelationships($fieldRels);
    return response()->json([
      'success' => true,
      'data' => $user
    ]);
  }
}

