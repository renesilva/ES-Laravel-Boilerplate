<?php

namespace App\Http\Controllers\Examples;

use App\Http\Controllers\Controller;
use App\Models\User;
use Eressea\MelianTaxonomy\Models\TaxonomyFieldRelationshipObject;
use Eressea\MelianTaxonomy\Models\Term;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TermsController extends Controller
{
  // Adiciona user-location 1 a un usuario.
  public function addTermToUser($id): JsonResponse
  {
    $user = User::find($id);
    $fieldRelationships = User::returnFields(['tax_rel'], true);
    $user->loadRelationships($fieldRelationships, true, true);
    TaxonomyFieldRelationshipObject::setObjectsTerms($user, $fieldRelationships, [
      'user-location' => 1
    ]);
    return response()->json([
      'success' => true,
      'data' => $user
    ]);
  }

  // Obtiene los términos de un usuario.
  public function getTermsUser($id): JsonResponse
  {
    $user = User::find($id);
    $fieldRelationships = User::returnFields(['tax_rel'], true);
    $user->loadRelationships($fieldRelationships);
    return response()->json([
      'success' => true,
      'data' => $user
    ]);
  }
}

