<?php

namespace App\Http\Controllers\Examples;

use App\Http\Controllers\Controller;
use App\Models\Business;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

class FetchDataController extends Controller
{
  /**
   * Obtiene 10 restaurantes de https://example-data.draftbit.com/restaurants
   * y los guarda en la base de datos local
   * @return JsonResponse
   */
  public function fetchBusinessDataGuzzle(): JsonResponse
  {
    $response = Http::get('https://example-data.draftbit.com/restaurants',
      ['_limit' => 10]
    );
    if ($response->ok()) {
      foreach ($response->json() as $business) {
        $business = Business::create(
          [
            'name' => $business['name'], 'city' => $business['city']
          ]
        );
      }
    }
    return response()->json([
      'success' => true,
      'message' => 'Business data fetched and updated successfully',
    ])->setStatusCode(200);
  }

  /**
   * Obtiene 10 restaurantes de https://example-data.draftbit.com/restaurants
   * y los guarda en la base de datos local
   * @return JsonResponse
   */
  public function fetchBusinessData(): JsonResponse
  {
    $curl = curl_init();

    curl_setopt_array($curl, array(
      CURLOPT_URL => 'https://example-data.draftbit.com/restaurants?_limit=10',
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => 'GET',
    ));

    $response = curl_exec($curl);

    curl_close($curl);

    $businesses = json_decode($response, true);

    foreach ($businesses as $business) {
      $business = Business::create(
        [
          'name' => $business['name'], 'city' => $business['city']
        ]
      );
    }

    return response()->json([
      'success' => true,
      'message' => 'Business data fetched and updated successfully',
      'businesses' => Business::all()
    ])->setStatusCode(200);
  }

  /**
   * Crea un business en la base de datos y crea uno nuevo en
   * https://example-data.draftbit.com/restaurants
   * @param Request $request
   * @return JsonResponse
   */
  public function createBusiness(Request $request): JsonResponse
  {
    $input = $request->only(['name', 'city']);
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
      // crear modelo en base de datos local
      $business = Business::create($input);
      // crear modelo en base de datos remota
      $response = Http::post('https://example-data.draftbit.com/restaurants', [
        'name' => $input['name'],
        'city' => $input['city']
      ]);
      $businessRemoteStatus = false;
      if ($response->created()) {
        $businessRemoteStatus = true;
      }
      return response()->json([
        'success' => true,
        'message' => 'Business created successfully',
        'business' => $business,
        'businessRemoteStatus' => $businessRemoteStatus,
      ])->setStatusCode(201);
    }
  }
}
