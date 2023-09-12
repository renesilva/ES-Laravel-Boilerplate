<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;


class ItemController extends Controller
{
  public function index(): JsonResponse
  {
    //
    $items = Item::all();
    return response()->json([
      'success' => true,
      'items' => $items
    ]);
  }

  public function store(Request $request)
  {
    $input = $request->all();
    $validator = Validator::make($input, [
      'name' => 'required',
      'budgeted_price' => 'required',
      'comments' => 'required',
    ]);
    if ($validator->fails()) {
      return response()->json([
        'success' => false,
        'message' => 'Validation error',
        'errors' => $validator->errors()
      ])->setStatusCode(400);
    } else {
      $item = Item::create($input);
      return response()->json([
        'success' => true,
        'item' => $item
      ]);
    }
  }

  public function show(string $id): JsonResponse
  {
    $item = Item::find($id);
    if (is_null($item)) {
      return response()->json([
        'success' => false,
        'message' => 'Item not found'
      ])->setStatusCode(404);
    } else {
      return response()->json([
        'success' => true,
        'item' => $item
      ]);
    }
  }

  public function update(Request $request, Item $item): JsonResponse
  {
    $input = $request->all();
    $validator = Validator::make($input, [
      'name' => 'required',
      'budgeted_price' => 'required',
      'comments' => 'required',
    ]);
    if ($validator->fails()) {
      return response()->json([
        'success' => false,
        'message' => 'Validation error',
        'errors' => $validator->errors()
      ])->setStatusCode(400);
    } else {
      $item->name = $input['name'];
      $item->budgeted_price = $input['budgeted_price'];
      $item->comments = $input['comments'];
      $item->save();
      return response()->json([
        'success' => true,
        'item' => $item
      ]);
    }
  }

  public function destroy(Item $item): JsonResponse
  {
    $item->delete();
    return response()->json([
      'success' => true,
      'message' => 'Item deleted'
    ]);
  }
}
