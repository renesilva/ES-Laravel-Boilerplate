<?php

namespace App\Http\Controllers\Examples;

use App\Http\Controllers\Controller;
use App\Models\Business;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Response;
use Illuminate\Support\Str;

class PDFController extends Controller
{
  // Crea un PDF temporal y fuerza descarga
  public function createTemporalPDF(): Response
  {
    $businesses = Business::all();
    $random_string = Str::random(10);
    $filename = 'invoice_' . $random_string . '.pdf';
    $pdf = Pdf::loadView('pdfs.businesses', ['businesses' => $businesses]);
    return $pdf->download($filename);
  }

  // Crea un PDF temporal y muestra el PDF (stream)
  public function createAndStreamPDF(): Response
  {
    $businesses = Business::all();
    $pdf = Pdf::loadView('pdfs.businesses', ['businesses' => $businesses]);
    return $pdf->stream('invoice.pdf');
  }

  // Guarda el PDF y fuerza descarga
  public function createAndStorePDF(): Response
  {
    $businesses = Business::all();
    $pdf = Pdf::loadView('pdfs.businesses', ['businesses' => $businesses]);
    $random_string = Str::random(10);
    $filename = 'invoice_' . $random_string . '.pdf';
    $pdf->save(storage_path('app/reports/' . $filename));
    return $pdf->download($filename);
  }
}

