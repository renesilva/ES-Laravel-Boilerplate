<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Supplier;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SupplierController extends Controller
{
  public function index(): JsonResponse
  {
    $suppliers = Supplier::all();
    return response()->json([
      'success' => true,
      'suppliers' => $suppliers
    ]);
  }

  public function store(Request $request): JsonResponse
  {
    $input = $request->all();
    $validator = Validator::make($input, [
      'name' => 'required',
      'city' => 'required',
      'category' => 'required',
    ]);
    if ($validator->fails()) {
      return response()->json([
        'success' => false,
        'message' => 'Validation error',
        'errors' => $validator->errors()
      ])->setStatusCode(400);
    } else {
      $supplier = Supplier::create($input);
      return response()->json([
        'success' => true,
        'supplier' => $supplier
      ]);
    }
  }

  public function show(string $id): JsonResponse
  {
    $supplier = Supplier::find($id);
    if (is_null($supplier)) {
      return response()->json([
        'success' => false,
        'message' => 'Supplier not found'
      ])->setStatusCode(404);
    } else {
      return response()->json([
        'success' => true,
        'supplier' => $supplier
      ]);
    }
  }

  public function update(Request $request, Supplier $supplier): JsonResponse
  {
    $input = $request->all();
    $validator = Validator::make($input, [
      'name' => 'required',
      'city' => 'required',
      'category' => 'required',
    ]);
    if ($validator->fails()) {
      return response()->json([
        'success' => false,
        'message' => 'Validation error',
        'errors' => $validator->errors()
      ])->setStatusCode(400);
    } else {
      $supplier->name = $input['name'];
      $supplier->city = $input['city'];
      $supplier->category = $input['category'];
      $supplier->save();
      return response()->json([
        'success' => true,
        'supplier' => $supplier
      ]);
    }
  }

  public function destroy(Supplier $supplier)
  {
    $supplier->delete();
    return response()->json([
      'success' => true,
      'message' => 'Supplier deleted'
    ]);
  }
}
