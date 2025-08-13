<?php

namespace App\Generators;

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ExcelGenerator
{

  private $formats = [
    'accounting' => '_-£* #,##0.00_-;-£* #,##0.00_-;_-£* " - "??_-;_-@_-',
    'date'       => 'dd/mm/yyyy',
    'meterage'   => '#,##0.00"m²"',
  ];

  public function format($key)
  {
    return $this->formats[$key];
  }

  /**
   *  Sets format for spread sheets. Number, Dates etc.
   *
   * @param $activeSheet
   * @param $start
   * @param $end
   */
  public function formatCommissions($activeSheet, $start, $end)
  {

    $activeSheet->getStyle("B$start:B$end")
                ->getNumberFormat()
                ->setFormatCode($this->format('date'));
    $activeSheet->getStyle("G$start:G$end")
                ->getNumberFormat()
                ->setFormatCode($this->format('meterage'));
    $activeSheet->getStyle("H$start:H$end")
                ->getNumberFormat()
                ->setFormatCode($this->format('accounting'));
  }

  public function genCommissionsSheet($commissionsData)
  {
    // Spreadsheet
    $spreadsheet = new Spreadsheet();
    $activeSheet = $spreadsheet->getActiveSheet();
    $row = 1;
    foreach ($commissionsData as $month => $stat) {
      $statCount = count($stat);
      $activeSheet->setCellValue("A".$row++, $month);
      $activeSheet->fromArray($stat->toArray(), null, "A".$row++);
      $start = $row;
      $end = $row += $statCount;
      $this->formatCommissions($activeSheet, $start, $end);
    }

    return IOFactory::createWriter($spreadsheet, 'Xlsx');
  }

  public function getProductsSheet($productsData)
  {
    $end = count($productsData);
    $spreadsheet = new Spreadsheet();
    $activeSheet = $spreadsheet->getActiveSheet();

    $activeSheet->fromArray($productsData->toArray(), null, 'A1');
    $activeSheet->getStyle("B2:B$end")
                ->getNumberFormat()
                ->setFormatCode($this->format('date'));
    $activeSheet->getStyle("H2:H$end")
                ->getNumberFormat()
                ->setFormatCode($this->format('accounting'));
    $activeSheet->getStyle("I2:I$end")
                ->getNumberFormat()
                ->setFormatCode($this->format('meterage'));
    $activeSheet->getStyle("J2:J$end")
                ->getNumberFormat()
                ->setFormatCode($this->format('accounting'));

    return IOFactory::createWriter($spreadsheet, 'Xlsx');
  }

  public function getMaterialsSheet($data)
  {
    $end = count($data);
    $spreadsheet = new Spreadsheet();
    $activeSheet = $spreadsheet->getActiveSheet();

    $activeSheet->fromArray($data->toArray(), null, 'A1');
    $activeSheet->getStyle("F2:F$end")
                ->getNumberFormat()
                ->setFormatCode($this->format('accounting'));
    $activeSheet->getStyle("H2:H$end")
                ->getNumberFormat()
                ->setFormatCode($this->format('accounting'));

    return new Xlsx($spreadsheet);
  }

}
