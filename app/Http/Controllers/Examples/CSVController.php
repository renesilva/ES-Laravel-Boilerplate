<?php

namespace App\Http\Controllers\Examples;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Keboola\Csv\CsvReader;
use Keboola\Csv\Exception;

class CSVController extends Controller
{
  /**
   * Obtiene los items de un archivo CSV y los muestra en formato JSON con la suma de precios
   * @return JsonResponse
   */
  public function showItemsCSV(): JsonResponse
  {
    try {
      $keboola_csv_file = new CsvReader(storage_path('app/examples/items.csv'));
    } catch (Exception $e) {
      return response()->json([
        'success' => false,
        'message' => 'CSV file not found'
      ])->setStatusCode(404);
    }

    $total = 0.00;
    $items = [];

    foreach ($keboola_csv_file as $index => $row) {
      if ($index > 0) {
        $items[] = [
          'name' => $row[0],
          'description' => $row[1],
          'price' => $row[2],
        ];
        $total += (float)$row[2];
      }
    }
    return response()->json([
      'success' => true,
      'message' => 'CSV file retrieved successfully',
      'items' => $items,
      'total' => number_format($total, 2)
    ]);
  }
}

