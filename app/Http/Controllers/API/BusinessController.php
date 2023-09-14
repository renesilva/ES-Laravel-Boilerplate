<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Business;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BusinessController extends Controller
{
  public function __construct()
  {
    $this->authorizeResource(Business::class, 'business');
  }

  public function index(): JsonResponse
  {
    $businesses = Business::all();
    return response()->json([
      'success' => true,
      'data' => $businesses,
    ]);
  }

  public function store(Request $request): JsonResponse
  {
    $input = $request->only(['name', 'city', 'logo']);
    $validator = Validator::make($input, [
      'name' => 'required',
      'city' => 'required',
    ]);
    if ($validator->fails()) {
      // falla
      return response()->json([
        'success' => false,
        'message' => 'Validation error',
        'errors' => $validator->errors(),
      ])->setStatusCode(400);
    } else {
      // no falla
      $business = Business::create($input);
      if ($request->hasFile('logo') && $request->file('logo')->isValid()) {
        $logoFile = $request->file('logo');
        $logoFile->store('public/business/images');
        $business->logo = $logoFile->hashName();
        $business->save();
      }
      return response()->json([
        'success' => true,
        'business' => $business,
      ]);
    }
  }

  public function show(Business $business): JsonResponse
  {
    // $this->authorize('view business', BusinessPolicy::class);
    return response()->json([
      'success' => true,
      'business' => $business,
    ]);
  }


  public function update(Request $request, Business $business): JsonResponse
  {
    $input = $request->only(['name', 'city']);
    $validator = Validator::make($input, [
      'name' => 'required',
      'city' => 'required',
    ]);
    if ($validator->fails()) {
      return response()->json([
        'success' => false,
        'message' => 'Validation error',
        'errors' => $validator->errors(),
      ])->setStatusCode(400);
    } else {
      $business->name = $input['name'];
      $business->city = $input['city'];
      if ($request->hasFile('logo') && $request->file('logo')->isValid()) {
        $logoFile = $request->file('logo');
        $logoFile->store('public/business/images');
        $business->logo = $logoFile->hashName();
      }
      $business->save();
      return response()->json([
        'success' => true,
        'business' => $business,
      ]);
    }
  }

  public function destroy(Business $business): JsonResponse
  {
    $business->delete();
    return response()->json([
      'success' => true,
      'message' => 'Business deleted',
    ]);
  }
}
