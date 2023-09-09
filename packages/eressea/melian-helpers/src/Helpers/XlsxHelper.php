<?php
/**
 * Created by Eressea Solutions
 * @author Rene Silva <rsilva@eresseasolutions.com>
 */

namespace Eressea\MelianHelpers\Helpers;

class XlsxHelper
{
  /**
   * Convert CSV to Xlsx
   *
   * @param string $csvPath
   * @param string $fileName
   * @return
   */
  public static function convertToXlsx($csvPath, $fileName, $directory = '/app/')
  {
    $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader('Csv');
    $objPHPExcel = $reader->load($csvPath);
    $objWriter = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($objPHPExcel, 'Xlsx');

    $xlsxFile = storage_path() . $directory . $fileName . '.xlsx';
    $objWriter->save($xlsxFile);

    return $xlsxFile;
  }
}
